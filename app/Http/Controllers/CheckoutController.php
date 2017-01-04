<?php

namespace App\Http\Controllers;

use Bueno\Billing\BillingServiceProvider as Billing;
use Bueno\Exceptions\InvalidCouponException;
use Bueno\Repositories\DbCommonRepository as CommonRepo;
use Bueno\Repositories\DbKitchenRepository as KitchenRepo;
use Bueno\Validations\CheckoutValidator as Validator;
use Bueno\Validations\ValidationException;
use Flash;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bueno\Repositories\DbUserRepository as UserRepo;
use Bueno\Repositories\DbOrderRepository as OrderREpo;
use Bueno\Billing\BillingInterface as Payment;
use Bueno\Exceptions\CheckoutValidationException;


/**
 * Class ItemsController
 * @package App\Http\Controllers
 */
class CheckoutController extends Controller
{

  /**
   * @var Payment
   */
  protected $payment;

  protected $orderRepo;

  protected $userRepo;

  protected $kitchenRepo;

  /**
   * @param Payment $payment
   */
  function __construct(Billing $billing, OrderRepo $orderRepo, UserRepo $userRepo, CommonRepo $commonRepo, KitchenRepo $kitchenRepo, Validator $validator)
  {
    $this->userRepo = $userRepo;

    $this->commonRepo = $commonRepo;

    $this->orderRepo = $orderRepo;

    $this->validator = $validator;

    $this->kitchenRepo = $kitchenRepo;

    $this->billing = $billing;

    $this->middleware('auth');
  }

  /**
   * shows the checkout page
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getCheckout()
  {
    try {
      $this->userRepo->checkIfUserIsVerified(auth()->user());

      $this->userRepo->checkIfUserHasAddresses(auth()->user());

      $this->orderRepo->checkIfUserHasMinimumAmountOrder(auth()->user(), session('area_id'));

      $this->commonRepo->checkIfKitchenIsOpen();

      $this->kitchenRepo->checkIfItemsAreInStock(auth()->user()->cartItems, session('area_id'));

      $this->kitchenRepo->checkItemsQuantity(auth()->user()->cartItems);
    }
    catch(CheckoutValidationException $e)
    {
      Flash::danger($e->getMessage());
    }
    return view('orders.checkout');
  }

  /**
   * redirect to the payment gateway depending on the payment mode
   *
   * @return mixed
   */
  public function postCheckout()
  {
    $inputs = request()->all();

    try
    {
      $this->validator->fire($inputs);

      $this->userRepo->checkIfPaymentSelectedIsEnabled($inputs);

      $this->userRepo->checkIfUserIsVerified(auth()->user());

      $this->commonRepo->checkIfKitchenIsOpen();

      $this->commonRepo->checkIfKitchenisActive($inputs);

      $this->userRepo->checkIfUserIsBlackListed(auth()->user());

      $this->userRepo->checkIfUserCartHasDisabledItems(auth()->user()->cartItems);

      $this->kitchenRepo->checkIfItemsAreInStock(auth()->user()->cartItems, session('area_id'));

      $this->commonRepo->checkIfUserIsAdmin();

      $this->commonRepo->checkForTotalItemsSum(auth()->user()->cartItems);

      $this->commonRepo->checkIfCartHasMaximumOrderAmount(auth()->user()->cartItems);

      $this->userRepo->checkIfAddressExists($inputs, auth()->user());
    }
    catch(ValidationException $e)
    {
      return redirect()->back()->withInput()->withErrors($e->getErrors());
    }
    catch(CheckoutValidationException $e)
    {
      Flash::danger($e->getMessage());

      return redirect()->back();
    }

    $payment = $this->billing->resolve();

    $order = $this->orderRepo->createUserOrder($inputs);

    return $payment->buildFormAndSubmit($order, $inputs);
  }

  /**
   * handles the success response from payment gateway
   *
   * @param $payment_mode_id
   */
  public function postSuccess($payment_mode_id)
  {
    $inputs = request()->all();

    $payment = $this->billing->resolve();

    return $payment->handleSuccess($inputs);
  }

  /**
   * @param $gateway
   */
  public function postFailure($gateway)
  {
      $payment = $this->billing->resolve();

      return $payment->handleFailure();
  }

  public function postCharge()
  {
    $inputs = request()->all();

    $payment = $this->billing->resolve();

    return $payment->chargePayment($inputs);
  }

  /**
   * apply coupon to the order
   *
   * @return array
   */
  public function applyCoupon()
  {
    $inputs = request()->all();

    try
    {
      $coupon = $this->orderRepo->applyCoupon(request());
    }
    catch(InvalidCouponException $e)
    {
      return $this->respondWithErrors($e->getMessage());
    }
    return $this->respondWithSuccess($coupon);
  }


}

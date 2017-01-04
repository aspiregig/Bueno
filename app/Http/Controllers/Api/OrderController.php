<?php

namespace App\Http\Controllers\Api;

use JWTAuth;
use Bueno\Repositories\DbUserRepository;
use Bueno\Repositories\DbOrderRepository;
use Bueno\Repositories\DbCommonRepository;
use Bueno\Validations\ValidationException;
use Bueno\Repositories\DbKitchenRepository;
use Bueno\Exceptions\InvalidCouponException;
use Bueno\Exceptions\CheckoutValidationException;
use Bueno\Billing\BillingServiceProvider as Billing;
use Bueno\Validations\CheckoutValidator as Validator;

class OrderController extends ApiController
{
  /**
   *
   */
  function __construct(
      DbOrderRepository $orderRepo,
      DbUserRepository $userRepo,
      DbCommonRepository $commonRepo,
      DbKitchenRepository $kitchenRepo,
      Validator $validator,
      Billing $billing
  )
  {
    parent::__construct();

    $this->orderRepo = $orderRepo;
    $this->userRepo = $userRepo;
    $this->commonRepo = $commonRepo;
    $this->kitchenRepo = $kitchenRepo;
    $this->validator = $validator;
    $this->billing = $billing;

    $this->middleware('jwt.auth');
  }

  /**
   * creates an order
   */
  public function postOrder()
  {
    $inputs = request()->all();

    $user = JWTAuth::parseToken()->authenticate();

    try
    {
      $this->validator->fire($inputs);

      $this->userRepo->checkIfPaymentSelectedIsEnabled($inputs);

      $this->userRepo->checkIfUserIsVerified($user);

      $this->commonRepo->checkIfKitchenIsOpen();

      $this->commonRepo->checkIfKitchenisActive($inputs);

      $this->userRepo->checkIfUserIsBlackListed($user);

      $this->userRepo->checkIfUserCartHasDisabledItems($user->cartItems);

      $this->kitchenRepo->checkIfItemsAreInStock($user->cartItems, $inputs['area_id']);

      $this->commonRepo->checkIfUserIsAdmin();

      $this->commonRepo->checkForTotalItemsSum($user->cartItems);

      $this->userRepo->checkIfAddressExists($inputs, $user);
    }
    catch(ValidationException $e)
    {
      return $this->respondWithErrors($e->getMessages());
    }
    catch(CheckoutValidationException $e)
    {
      return $this->respondWithErrors($e->getMessage());
    }

    $payment = $this->billing->resolve();

    $order = $this->orderRepo->createUserOrder($inputs);

    return $payment->handlePaymentAPI($order, $inputs);
  }

  /**
   * updates order status
   */
  public function patchOrder()
  {
    $inputs = request()->all();

    $payment = $this->billing->resolve();

    return $payment->handleSuccessAPI($inputs);
  }

  /**
   * applies coupon
   */
  public function postCoupon()
  {
    $inputs = request()->all();

    $user = JWTAuth::parseToken()->authenticate();

    $inputs = $this->setInputsForCoupon($inputs, $user);

    try
    {
      $coupon = $this->orderRepo->applyCouponApi($inputs);
    }
    catch(InvalidCouponException $e)
    {
      return $this->respondWithErrors($e->getMessage());
    }

    return $this->respond([
      'data'  => [
        'discount' => $coupon['discount'],
        'cashback' => $coupon['cashback'],
        'items'    => $coupon['items']
      ]
    ]);
  }

  /**
   * transform inputs to apply coupon
   *
   * @param $inputs
   * @return mixed
   */
  private function setInputsForCoupon($inputs, $user)
  {
      $inputs['area_id'] = isset($inputs['area_id']) ? $inputs['area_id'] : null;
      $inputs['phone'] = $user->phone;

      return $inputs;
    }
}

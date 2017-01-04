<?php

namespace App\Http\Controllers;

use Bueno\Exceptions\AddToCartException;
use Bueno\Exceptions\CheckoutValidationException;
use Bueno\Repositories\DbCommonRepository;
use Bueno\Repositories\DbKitchenRepository;
use Bueno\Repositories\DbLocationRepository;
use Bueno\Repositories\DbOrderRepository;
use Flash;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bueno\Repositories\ItemRepository;
use Bueno\Repositories\DbUserRepository;
use Bueno\Validations\ValidationException;



/**
 * Class ItemsController
 * @package App\Http\Controllers
 */
class CartController extends Controller
{
  /**
   * @var ItemRepository
   */
  protected $itemRepo;

  /**
   * @var DbUserRepository
   */
  protected $userRepo;

  /**
   * @param ItemRepository $itemRepo
   */
  function __construct(ItemRepository $itemRepo, DbUserRepository $userRepo, DbKitchenRepository $kitchenRepo, DbCommonRepository $commonRepo, DbOrderRepository $orderRepo, DbLocationRepository $locationRepo)
  {
    $this->itemRepo = $itemRepo;

    $this->userRepo = $userRepo;

    $this->orderRepo = $orderRepo;

    $this->commonRepo = $commonRepo;

    $this->locationRepo = $locationRepo;

    $this->kitchenRepo = $kitchenRepo;

    $this->middleware('auth');

    $this->middleware('verify', ['only' => 'getCart']);
  }

  /**
   * shows cart page
   *
   * @return array
   */
  public function getCart()
  {
    $kitchen = $this->kitchenRepo->getAreaKitchen(session('area_id'));

    $payment_modes = $this->commonRepo->getAllPaymentModes();

    $cart_items = $this->userRepo->getAllCartItems(auth()->user()->id);

    $area = $this->locationRepo->getSessionArea();

    $ngos = $this->commonRepo->getAllNgo();

    $session_area = $this->locationRepo->getAreaById(session('area_id'));

    $checkout_errors = null;

    try {

      //$this->orderRepo->checkIfUserHasMinimumAmountOrder(auth()->user(), session('area_id'));

      $this->commonRepo->checkIfKitchenIsOpen();
//
      $this->commonRepo->checkIfUserIsAdmin();

      $this->userRepo->checkIfUserIsBlackListed(auth()->user());
//
//      $this->kitchenRepo->checkIfItemsAreInStock(auth()->user()->cartItems);

      //$this->kitchenRepo->checkItemsQuantity(auth()->user()->cartItems);
    }
    catch(CheckoutValidationException $e)
    {
      Flash::danger($e->getMessage());

      return redirect(route('users.account.get'));
    }
    return view('users.cart', compact('cart_items','area', 'kitchen', 'ngos', 'session_area', 'checkout_errors', 'payment_modes'));
  }

  /**
   * add items to the cart
   *
   * @return \Illuminate\Http\RedirectResponse
   */
  public function addToCart()
  {
    $inputs = request()->all();

    try
    {
      $items = $this->userRepo->addToUserCart(auth()->user(), $inputs, session('area_id'));
    }
    catch(AddToCartException $e)
    {
      return $this->respondWithErrors($e->getMessage());
    }

    return $this->respondWithSuccess([
      'items' => $items
    ]);
  }

  /**
   *
   */
  public function removeFromCart()
  {
    $cart_id = request()->get('cart_id');

    $this->userRepo->deleteCartItem(auth()->user(), $cart_id);

    return [
        'success' => true,
        'data'  => [
            'items' => auth()->user()->cartItems
        ]
    ];
  }

  public function saveCartQuantity()
  {
    $inputs = request()->all();

    $this->userRepo->saveCartQuantity($inputs['cart_id'], $inputs['quantity']);

    return [
        'success' => true,
        'data'  => [
            'items' => auth()->user()->cartItems
        ]
    ];

  }

}

<?php

namespace App\Http\Controllers\Api;


use JWTAuth;
use Bueno\Exceptions\AddToCartException;
use Bueno\Transformers\CartTransformer;
use Bueno\Transformers\ItemTransformer;
use Bueno\Transformers\UserTransformer;
use Bueno\Transformers\OrderTransformer;
use Bueno\Repositories\DbUserRepository;
use Bueno\Authenticate\AuthenticateUser;
use Bueno\Repositories\DbOrderRepository;
use Bueno\Transformers\AddressTransformer;
use Bueno\Validations\ValidationException;
use Bueno\Transformers\SavedItemTransformer;
use Bueno\Exceptions\ModelNotFoundException;
use Bueno\Validations\CreateUserAddressValidator;
use Bueno\Exceptions\InvalidConfirmationCodeException;

/**
 * Class ClientsController
 * @package App\Http\Controllers\Api
 */
class UserController extends ApiController {


  /**
   * @param AuthenticateUser $authenticate
   * @param DbUserRepository $userRepo
   */
  function __construct(
      AuthenticateUser $authenticate,
      DbUserRepository $userRepo,
      DbOrderRepository $orderRepo,
      CreateUserAddressValidator $addressValidator
  )
  {
    parent::__construct();

    $this->userRepo = $userRepo;

    $this->orderRepo = $orderRepo;

    $this->authenticate = $authenticate;

    $this->addressValidator = $addressValidator;

    $this->middleware('jwt.auth');
  }


  /**
   * returns the authenticated client and its corresponding role info
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function getUser()
  {
    $user = JWTAuth::parseToken()->authenticate();

    return $this->respondWithItem($user, new UserTransformer);
  }

  /**
   * verify user otp
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function postVerify()
  {
    $inputs = request()->all();

    $user = JWTAuth::parseToken()->authenticate();

    try
    {
      $this->authenticate->verifyUser($inputs, $user);
    }
    catch(InvalidConfirmationCodeException $e)
    {
      return $this->respondWithErrors($e->getMessage());
    }

    return $this->respond([
      'message' => 'You phone has been verified'
    ]);
  }

  /**
   * resend OTP Code
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function postResendOTP()
  {
    $user = $user = JWTAuth::parseToken()->authenticate();

    try
    {
      $this->userRepo->resendOTPCode($user);
    }
    catch(\Exception $e)
    {
      return $this->respondWithErrors($e->getMessage());
    }

    return $this->respond([
      'message' => "OTP Code Sent !"
    ]);
  }

  /**
   * get all saved items
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function getSavedItems()
  {
    $user = JWTAuth::parseToken()->authenticate();

    $saved_items = $user->saved_items;

    return $this->respondWithCollection($saved_items, new SavedItemTransformer);
  }

  /**
   * add a item in user saved items
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function postSavedItems()
  {
    $user = JWTAuth::parseToken()->authenticate();

    try
    {
      $saved_items = $this->userRepo->addItemToUserFavorites($user, request()->get('item_id'));
    }
    catch(\Exception $e)
    {
      return $this->errorWrongArgs($e->getMessage());
    }

    return $this->respond([
      'message' => 'Item Saved'
    ]);
  }

  /**
   * delete a item from user saved items
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function deleteSavedItems()
  {
    $user = JWTAuth::parseToken()->authenticate();

    try
    {
      $saved_items = $this->userRepo->deleteUserFavorite($user, request()->get('item_id'));
    }
    catch(\Exception $e)
    {
      return $this->errorWrongArgs($e->getMessage());
    }

    return $this->respond([
        'message' => 'Item Removed'
    ]);
  }

  /**
   * return user orders with pagination meta
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function getOrders()
  {
    $inputs = request()->all();

    $user = JWTAuth::parseToken()->authenticate();

    $orders = $this->userRepo->getUserOrders($user->id, $this->perPage);

    return $this->respondWithPagination($orders, $inputs, new OrderTransformer);
  }

  /**
   * returns single order order
   *
   * @param $order_id
   * @return \Illuminate\Http\JsonResponse
   */
  public function getOrder($order_id)
  {
    $user  = JWTAuth::parseToken()->authenticate();

    $order = $this->userRepo->getUserOrderById($user, $order_id);

    return $this->respondWithItem($order, new OrderTransformer);
  }

  /**
   * return all user addresses
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function getAddresses()
  {
    $user  = JWTAuth::parseToken()->authenticate();

    $addresses = $user->addresses;

    return $this->respondWithCollection($addresses, new AddressTransformer);
  }

  /**
   * creates a user address
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function postAddresses()
  {
    $inputs = request()->all();

    $user  = JWTAuth::parseToken()->authenticate();

    try
    {
      $this->addressValidator->fire($inputs);
    }
    catch(ValidationException $e)
    {
      return $this->respondWithErrors($e->getMessages());
    }

    $address = $this->userRepo->createUserAddress($user, $inputs);

    return $this->respond([
      'message' => 'Address Created'
    ]);
  }

  /**
   * get user address by id
   *
   * @param $address_id
   * @return \Illuminate\Http\JsonResponse
   */
  public function getAddress($address_id)
  {
    $user = JWTAuth::parseToken()->authenticate();

    try
    {
      $address = $this->userRepo->getUserAddressById($user->id, $address_id);
    }
    catch(ModelNotFoundException $e)
    {
      return $this->respondWithErrors($e->getMessage());
    }

    return $this->respondWithItem($address, new AddressTransformer);
  }

  /**
   * updates user address
   *
   * @param $address_id
   * @return \Illuminate\Http\JsonResponse
   */
  public function patchAddress($address_id)
  {
    $inputs = request()->all();

    $user = JWTAuth::parseToken()->authenticate();

    try
    {
      $this->userRepo->updateUserAddress($user, $address_id, $inputs);
    }
    catch(ModelNotFoundException $e)
    {
      return $this->respondWithErrors($e->getMessage());
    }

    return $this->respond([
      'message' => "Address Updated"
    ]);
  }

  /**
   * delete user address
   *
   * @param $address_id
   * @return \Illuminate\Http\JsonResponse
   */
  public function deleteAddress($address_id)
  {
    $inputs = request()->all();

    $user = JWTAuth::parseToken()->authenticate();

    try
    {
      $this->userRepo->deleteUserAddressById($user, $address_id, $inputs);
    }
    catch(ModelNotFoundException $e)
    {
      return $this->respondWithErrors($e->getMessage());
    }

    return $this->respond([
        'message' => "Address Deleted"
    ]);
  }

  /**
   * get user cart items
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function getCart()
  {
    $user = JWTAuth::parseToken()->authenticate();

    $cart_items = $user->cartItems;

    return $this->respondWithCollection($cart_items, new CartTransformer);
  }

  /**
   * add or updates item to user cart
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function postCart()
  {
    $inputs = request()->all();

    $user = JWTAuth::parseToken()->authenticate();

    try
    {
      $this->userRepo->addToUserCart($user, $inputs, request()->get('area_id'));
    }
    catch(AddToCartException $e)
    {
      return $this->respondWithErrors($e->getMessage());
    }

    return $this->respond([
      'message' => 'Item Added to Cart'
    ]);
  }


  /**
   * delete item from user cart
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function deleteCart()
  {
    $user = JWTAuth::parseToken()->authenticate();

    try
    {
      $this->userRepo->deleteCartItem($user, request()->get('cart_id'));
    }
    catch(ModelNotFoundException $e)
    {
      return $this->respondWithErrors($e->getMessage());
    }

    return $this->respond([
        'message' => 'Item deleted from Cart'
    ]);
  }
}
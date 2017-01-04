<?php

namespace App\Http\Controllers;

use Flash;
use App\Http\Requests;
use Bueno\Mailers\UserMailer;
use App\Http\Controllers\Controller;
use Bueno\Exceptions\AddToCartException;
use Bueno\Validations\ValidationException;
use Bueno\Exceptions\AddToFavoriteException;
use Bueno\Exceptions\ModelNotFoundException;
use Bueno\Exceptions\UpdatePasswordException;
use Bueno\Validations\UpdatePasswordValidator;
use Bueno\Exceptions\ModelCannotBeDeletedException;
use Bueno\Repositories\DbUserRepository as UserRepo;
use Bueno\Repositories\DbLocationRepository as LocaRepo;
use Bueno\Validations\CreateUserValidator as userValidator;
use Bueno\Repositories\DbMembershipRepository as MembershipRepo;
use Bueno\Validations\CreateUserAddressValidator as AddressValidator;
use SEOMeta;
use OpenGraph;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * Class UsersController
 * @package App\Http\Controllers
 */
class UsersController extends Controller
{

  /**
   * @var
   */
  protected $userRepo,$membershipRepo;

  protected $userValidator;

  protected  $locationRepo;

  /**
   * @var AddressValidator
   */
  protected $addressValidator;

  /**
   * @param DbUserRepository $userRepo
   */
  function __construct(UserRepo $userRepo, userValidator $userValidator, LocaRepo $locationRepo, AddressValidator $addressValidator, UserMailer $userMailer, MembershipRepo $membershipRepo, UpdatePasswordValidator $passwordValidator)
  {
    $this->userRepo = $userRepo;

    $this->membershipRepo = $membershipRepo;

    $this->userMailer = $userMailer;

    $this->locationRepo = $locationRepo;

    $this->userValidator = $userValidator;

    $this->passwordValidator = $passwordValidator;

    $this->addressValidator = $addressValidator;

    $this->middleware('auth', ['except' => ['postArea', 'getOrderFeedback']]);
  }

  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getAccount()
  {
   $this->setSeo('Your account', '',  url('/') . 'images/logo.png');

    $areas = $this->locationRepo->getAllAreas();

    return view('users.account', compact('areas'));
  }

  /**
   *
   */
  public function postAccount()
  {
    $inputs = request()->all();

    try
    {
      $this->userValidator->fire($inputs);
    }
    catch(ValidationException $e)
    {
      return redirect(route('users.account.get'))->withInput()->withErrors($e->getErrors());
    }

    $user = auth()->user();

    $this->userRepo->updateUserInfo($user, $inputs);

    Flash::success('Information Updated');

    return redirect(route('users.account.get'));
  }

  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getEditAccount()
  {
    $this->setSeo('Edit your account', '',  url('/') . 'images/logo.png');

    return view('users.edit_account');
  }


  /**
   * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function postEditAccount()
  {
    $inputs = request()->all();

    try
    {
      $this->userValidator->fire($inputs);
    }
    catch(ValidationException $e)
    {
      return redirect(route('users.account.edit.get'))->withInput()->withErrors($e->getErrors());
    }

    $user = auth()->user();

    $this->userRepo->updateUserInfo($user, $inputs);

    Flash::success('Information Updated');

    return redirect(route('users.account.edit.get'));
  }

  /**
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function postEditPassword()
  {
    $inputs = request()->all();

    try
    {
      $this->passwordValidator->fire($inputs);
    }
    catch(ValidationException $e)
    {
      return redirect(route('users.account.edit.get'))->withErrors($e->getErrors())->withInput();
    }

    try
    {
      $this->userRepo->updatePassword($inputs, auth()->user());
    }
    catch(UpdatePasswordException $e)
    {
      Flash::danger($e->getMessage());

      return redirect(route('users.account.edit.get'));
    }

    Flash::success('Your password has been updated successfully');

    SEOMeta::setTitle('Change Password');

    return redirect(route('users.account.edit.get'));
  }
  /**
   * shows the form to create new address
   */
  public function getNewAddress()
  {
    $this->setSeo('Add New Address', '',  url('/') . 'images/logo.png');

    $areas = $this->locationRepo->getAllAreas();

    return view('users.new_address', compact('areas'));
  }


  /**
   * validates and creates a new address for the user
   *
   * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function postNewAddress()
  {
    $inputs = request()->all();

    try
    {
      $this->addressValidator->fire($inputs);
    }
    catch(ValidationException $e)
    {
      if(request()->ajax()) return $this->respondWithErrors($e->getErrors());

      return redirect(route('users.address.new.get'))->withInput()->withErrors($e->getErrors());
    }

    $address = $this->userRepo->createUserAddress(auth()->user(), $inputs);

    if(request()->ajax())  return $this->respondWithSuccess($address);

    Flash::message('Address Created !');

    return redirect(route('users.account.get'));
  }

  /**
   * @param $address_id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function editAddress($address_id)
  {
    $this->setSeo('Edit Address', '',  url('/') . 'images/logo.png');

    try
    {
      $address = $this->userRepo->getUserAddressById(auth()->user()->id, $address_id);
    }
    catch(ModelNotFoundException $e)
    {
      throw new NotFoundHttpException;
    }

    $areas = $this->locationRepo->getAllAreas();

    return view('users.edit_address', compact('address', 'areas'));
  }

  public function deleteAddress($address_id)
  {
    $address = $this->userRepo->deleteUserAddressById(auth()->user(), $address_id);

    Flash::success('Address Deleted');

    return redirect()->route('users.account.get'); 
  }


  /**
   * validates and updates the user address
   *
   * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function updateAddress($address_id)
  {
    $inputs = request()->all();

    try
    {
      $this->addressValidator->fire($inputs);
    }
    catch(ValidationException $e)
    {
      return redirect(route('users.address.edit.get', $address_id))->withInput()->withErrors($e->getErrors());
    }

    $this->userRepo->updateUserAddress(auth()->user(), $address_id, $inputs);

    Flash::success('Address Updated !');

    return redirect(route('users.address.edit.get', $address_id));
  }

  /**
   * get all user favorites items
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getUserSavedItems()
  {
    $this->setSeo('Your Saved Items', '',  url('/') . 'images/logo.png');

    $items = $this->userRepo->getUserFavoriteItems( auth()->user()->id );

    $area = $this->locationRepo->getSessionArea();

    $can_add = true;

    foreach($items as $item)
    {
      $can_add = $item->stock() && $can_add;
    }

    return view('users.saved_items', compact('items', 'can_add', 'area'));
  }

  /**
   * add the items to user 's favorites
   *
   * @param $item_id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function postUserSavedItems()
  {
    $item_id = request()->get('item_id');

    try
    {
      $items = $this->userRepo->addItemToUserFavorites(auth()->user(), $item_id);
    }
    catch(AddToFavoriteException $e)
    {
      return $this->respondWithErrors($e->getMessage());
    }

    return $this->respondWithSuccess($items);
  }

  /**
   * @return \Illuminate\Http\RedirectResponse
   */
  public function deleteUserSavedItems()
  {
    $item_id = request()->get('item_id');

    try
    {
      $this->userRepo->deleteUserFavorite(auth()->user(), $item_id);
    }
    catch(ModelCannotBeDeletedException $e)
    {
      return $this->respondWithErrors($e->getMessage());
    }

    return $this->respondWithSuccess([
      'message' => 'Item removed'
    ]);
  }

  /**
   * @return \Illuminate\Http\JsonResponse
   */
  public function addAllSavedToCart()
  {
      $inputs = request()->all();

      try
      {
        $items = $this->userRepo->addItemsToCart($inputs['items']);
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
   * shows user orders history
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getUserOrders()
  {
    $this->setSeo('Your Orders', '',  url('/') . 'images/logo.png');

    $orders = $this->userRepo->getUserOrders(auth()->user()->id, 10);

    return view('users.orders', compact('orders'));
  }

  /**
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
   */
  public function getUserSingleOrder($id)
  {
    $this->setSeo('Your orders');

    try
    {
      $order = $this->userRepo->getUserOrderById(auth()->user(), $id);
    }
    catch(ModelNotFoundException $e)
    {
      Flash::danger($e->getMessage());

      return redirect('users.account.get');
    }

    return view('users.single_order', compact('order'));
  }

  /**
   * @return mixed
   */
  public function postArea()
  {
    $inputs = request()->all();

    $area = $this->userRepo->setUserArea($inputs);

    return redirect()->back();
  }

  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getBuenoLoyalty()
  {
    $this->setSeo('Bueno Loyalty Points');

    $memberships = $this->membershipRepo->getAllMemberships();

    return view('users.bueno_loyalty',compact('memberships'));
  }

  /**
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function postBuenoLoyalty()
  {
    $inputs = request()->all();

    $this->userMailer->sendInvite(auth()->user(), $inputs);

    Flash::success('Invite Code Sent !');

    return redirect(route('users.loyalty.get'));
  }

  public function getOrderFeedback()
  {
    $inputs = request()->all();

    return $this->userRepo->saveUserFeedbackOnMissedCall($inputs);
  }

}

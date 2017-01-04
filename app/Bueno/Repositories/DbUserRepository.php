<?php namespace Bueno\Repositories;

use Segment;
use Bueno\Loggers\LoyalityLogger;
use Carbon\Carbon;
use App\Models\Area;
use App\Models\Cart;
use App\Models\CheckCoupon;
use App\Models\Coupon;
use App\Models\CouponGiveaway;
use App\Models\Favorite;
use App\Models\Item;
use App\Models\Membership;
use App\Models\Order;
use App\Models\Setting;
use App\Models\PaymentMode;
use Bueno\Exceptions\AddToCartException;
use Bueno\Exceptions\AddToFavoriteException;
use Bueno\Exceptions\CheckoutValidationException;
use Bueno\Exceptions\UpdatePasswordException;
use Bueno\Services\SmsAPI;
use Bueno\Services\VoiceOtpApi;
use Bueno\Validations\ValidationException;
use Hash;
use App\Models\Kitchen;
use App\Models\Otp;
use App\Models\User;
use App\Models\Address;
use App\Models\Group;
use Bueno\Exceptions\AssignedToRoleException;
use Bueno\Exceptions\ModelCannotBeDeletedException;
use Bueno\Exceptions\ModelNotFoundException;
use DateTime;
use Log as LaravelLog;
/**
 * Class DbUserRepository
 * @package Bueno\Repositories
 */
class DbUserRepository  {

  /**
   * @var
   */
  protected $address;

  /**
   * @var SmsAPI
   */
  protected $sms;

  /**
   * @param SmsAPI $sms
   */
  function __construct(SmsAPI $sms, VoiceOtpApi $voice)
  {
    $this->sms = $sms;

    $this->voice = $voice;
  }


  /**
   * @return \Illuminate\Database\Eloquent\Collection|static[]
   */
  public function getAllUsers()
  {
    return User::all();
  }

  /**
   * @param $keyword
   * @return array
   */
  public function getUsersByKeyword($keyword)
  {

    $users = new User;

    $users =  $users->where('group_id',2);

    $users= $users->orWhere('email','LIKE',$keyword.'%');

    $users =  $users->where('group_id',2);


    $users= $users->orWhere('phone','LIKE',$keyword.'%');

    $users =  $users->where('group_id',2);


    $users= $users->take(20)->get();

    $transform_users = [];

    $users->map(function($user) use (&$transform_users){
      $transform_users[] = [
        'email' => $user->phone . ' | ' . $user->email ,
        'id'    => $user->id
      ];
    });

    return $transform_users;

  }

  /**
   * @return \Illuminate\Database\Eloquent\Collection|static|static[]
   */
  public function getAdminsByKeyword($keyword)
  {

   $users = new User;

    $users =  $users->where('group_id',1);

    $users = $users->orWhere('email','LIKE',$keyword.'%');
    $users =  $users->where('group_id',1);


    $users = $users->orWhere('phone','LIKE',$keyword.'%');
    $users =  $users->where('group_id',1);


    $users = $users->take(20)->get();

    $transform_users = [];

    $users->map(function($user) use (&$transform_users){
      $transform_users[] = [
        'email' => $user->phone . ' | ' . $user->email ,
        'id'    => $user->id
      ];
    });

    return $transform_users;

  }
  public  function getAllCustomers()
  {
    $users = $this->getALlUsers();

    $users = $users->filter( function($user){
      return $user->group->name == "User" ? true : false;});

    return $users;
  }

  public  function getTotalUsers()
  {
    $users = new User;

    $users = $users->where('group_id',2);

    return $users->paginate(1);
  }

  /**
   * @param $start
   * @param $end
   * @return mixed
   */
  public function getAllUsersByDates($start,$end)
  {
    $users =  User::where('created_at','>=',$start)->where('created_at','<=',$end)->where('group_id',2)->get();
    
    return $users;
  }

  /**
   * @param $id
   * @return mixed
   */
  public function getUserById($id)
  {
    return User::find($id);
  }

  /**
   * @return mixed
   */
  public function getAllUsersByMonths()
  {
    $today = new DateTime('first day of this month');
    $today->modify('00:00:00');
    $users =  User::where('created_at','>',$today)->get();
    $users = $users->filter( function($user){
      return $user->group->name == "User" ? true : false;});

    return $users;

  }

  /**
   * @param $id
   * @param $inputs
   * @return mixed
   */
  public function updateUser($id,$inputs)
  {

    $user = $this->getUserById($id);

    $new_image_check = $inputs->file('avatar_url');

    if(isset($new_image_check))
    {
    $file = $inputs->file('avatar_url');

    $filename = upload_file($file, 'users');

    $user->avatar_url = $filename;

    $user->avatar_mime_type = $file->getClientMimeType();

    }

    $password = $inputs->get('password');

    $previous_pass = $user->password;

    // $previous_points = $user->points;

    $user->fill($inputs->all());

    if($password!=null)
    {
      $user->password = Hash::make($password);
    }
    else
    {
      $user->password = $previous_pass;
    }

    $user->sms_notify = $inputs->get('sms_notify') ? 1 : 0;
    $user->email_notify = $inputs->get('email_notify') ? 1 : 0;
    $user->save();





    // if($previous_points!=$user->points)
    // {
    //   $this->updateLoyalityPointsLog($user,$previous_points);
    // }

    return $user;
  }

  public function updateLoyalityPointsLog($user,$previous_points)
  {
    $loyalityLogger = new LoyalityLogger;
    $loyalityLogger->log(auth()->user()->full_name.'(User Id :'.auth()->user()->id.') Updated Points : User Id - '.$user->id.' User Name -  '.$user->full_name.' From :'.$previous_points.' To :'.$user->points);
  }

  /**
   * @param $id
   * @return mixed
   */
  public function totalOrdersByUserId($id)
  {
    $user = User::findOrFail($id);
    return $user->orders->count();
  }

  /**
   * @param $phone
   * @return mixed
   */
  public function getUserByPhone($phone)
  {
    return User::wherePhone($phone)->first();
  }

  /**
   * @param $email
   * @return mixed
   */
  public function getUserByEmail($email)
  {
    return User::whereEmail($email)->first();
  }

  /**
   * @param $inputs
   * @return static
   */
  public function newUser($inputs)
  {

    $user = User::create($inputs->all());

    $file = $inputs->file('avatar_url');

    $new_image_check = $inputs->file('avatar_url');

    if(isset($new_image_check))
    {
    $filename = upload_file($file, 'users');

    $user->avatar_url = $filename;

    $user->avatar_mime_type = $file->getClientMimeType();
    }
    else
    {
      $user->avatar_url = 'bueno-default';

      $user->avatar_mime_type = 'image/jpeg';
    }

    $user->password = Hash::make($inputs->get('password'));

    $user->mobile_otp = rand(1000,9999);

    $user->mobile_verify = 0;

    $user->email_verify = 0;

    $user->membership_id = $this->getUserMembership($user->orders->count());

    $user->points = 0;
    
    $user->save();

    $this->createUserReferralCode($user);

    return $user;
  }

  /**
   * @param $user_id
   * @param $address
   * @return mixed
   */
  public function getUserAddress($user_id,$address)
  {
    return Address::whereUserId($user_id)->whereAddress($address)->first();
  }

  public function deleteUserAddressById($user, $address_id)
  {
    $address = Address::where('id', $address_id)->where('user_id', $user->id)->first();

    if(!$address) throw new ModelNotFoundException('Address not found !');

    $address->delete();
  }

  public function getUserAddressById($user_id, $address_id)
  {
    $address = Address::where('id', $address_id)->where('user_id', $user_id)->first();

    if(!$address) throw new ModelNotFoundException('Resouce not found');

    return $address;
  }

  /**
   * @param $id
   * @return mixed
   */
  public function getUserByGroupId($id)
  {
    return User::whereGroupId($id)->get();
  }

  /**
   * @param $name
   * @return mixed
   */
  public function getManagerByGroupId($name)
  {
    $group = Group::where('name',$name)->first();
    if($group==null)
    {
      return User::whereId(0)->get();
    }
    return User::whereGroupId($group->id)->get();
  }

  /**
   * @return mixed
   */
  public function getNotAssignedManagers()
  {
    $managers = $this->getManagerByGroupId("Kitchen Manager");

    $managers = $managers->filter(function($manager){
        return is_null($manager->manageKitchen) ? true : false;
    });

    return $managers;
  }

  /**
   * @param $user_id
   * @param $new_address
   * @return Address
   */
  public function newUserAddress($user_id,$new_address)
  {
    $address = new Address;
    $address->user_id = $user_id;
    $address->area_id = $new_address['area_id'];
    $address->address = $new_address['address'];
    if(isset($new_address['company_name']))
    $address->address_name = $new_address['company_name'];
    $address->save();
    return $address;
  }

  /**
   * @param $phone
   * @return mixed
   */
  public function getUserDetailByPhone($phone)
  {
        $user = $this->getUserByPhone($phone);
        if($user!=null)
        {
          $data['no_data'] = 0;
          $data['first_name'] = $user->first_name;
          $data['last_name'] = $user->last_name;
          $data['email'] = $user->email;
          $data['addresses'] = $user->addresses;
          $data['loyalty_points'] = ceil($user->points/2);
        if($user->defaultAdderss()!=null)
        {
            $data['address'] = $user->defaultAdderss()->address;
            $data['area_id'] = $user->defaultAdderss()->area_id;
            $addresses = $addresses->filter(function($address){
              return $address->area->status == 1;
            });
            $data['addresses'] = $addresses;
            $data['message'] = 'Success.';
            $data['message_type'] = 'success';
        }
        else if($user->addresses->first()!=null)
        {
            $data['address'] = $user->addresses->first()->address;
            $data['area_id'] = $user->addresses->first()->area_id;
            $addresses = $user->addresses;
            $addresses = $addresses->filter(function($address){
              return $address->area->status == 1;
            });
            $data['addresses'] =  $addresses;
            $data['message'] = 'Success.';
            $data['message_type'] = 'success';
        }
        else
        {
            $data['message'] = 'No Address Found.';
            $data['message_type'] = 'info';
        }
        return $data;
      }
      $data['message'] = 'No Data Found.';
      $data['message_type'] = 'error';
      $data['no_data'] = 1;
      return $data;
  }

  /**
   * @param $email
   * @return mixed
   */
  public function getUserDetailByEmail($email)
  {
        $user = $this->getUserByEmail($email);

       if($user!=null)
        {
          $data['no_data'] = 0;
          $data['first_name'] = $user->first_name;
          $data['last_name'] = $user->last_name;
          $data['phone'] = $user->phone;
          $data['addresses'] = $user->addresses;

        if($user->defaultAdderss()!=null)
        {
            $data['address'] = $user->defaultAdderss()->address;
            $data['state_id'] = $user->defaultAdderss()->state_id;
            $data['area_id'] = $user->defaultAdderss()->area_id;
            $data['city_id'] = $user->defaultAdderss()->city_id;
            $data['message'] = 'Success.';
            $data['message_type'] = 'success';
          $data['addresses'] = $user->addresses;
        }
        else if($user->addresses->first()!=null)
        {
            $data['address'] = $user->addresses->first()->address;
            $data['state_id'] = $user->addresses->first()->state_id;
            $data['area_id'] = $user->addresses->first()->area_id;
            $data['city_id'] = $user->addresses->first()->city_id;
            $data['addresses'] = $user->addresses;
            $data['message'] = 'Success.';
            $data['message_type'] = 'success';
        }
        else
        {
            $data['message'] = 'No Address Found.';
            $data['message_type'] = 'info';
        }
        return $data;
      }
      $data['message'] = 'No Data Found.';
      $data['message_type'] = 'error';
      $data['no_data'] = 1;
      return $data;
  }

  /**
   * @param $data
   * @return array
   */
  public function transformForExcel($users)
    {
      $transformData = [];


      $index = 0;

      foreach ($users as $user)
      {
        if($user->first_order['created_at']!=null)
         { $first_order_date = new DateTime($user->first_order['created_at']);
          $last_order_date = new DateTime($user->last_order['created_at']);
          $source = $user->first_order->source->name;
          $first_order_date = $first_order_date->format('d/m/y');
          $last_order_date = $last_order_date->format('d/m/y');
        }
        else
        {
          $first_order_date = null;
          $last_order_date = null;
          $source = null;
        }
          $transformData[$index] = [
            'User Id'                => $user->id,
            'First Name'              => $user->first_name,
            'Last Name'              => $user->last_name,
            'User Name'              => $user->full_name,
            'Email'             => $user->email,
            'Mobile No'         => $user->phone,
            'Date of Registration' => $user->created_at,
            'Source'                => $source,
            'First Order'           => $user->first_order['id'],
            'First Order Date'      => $first_order_date,
            'Last Order'           => $user->last_order['id'],
            'Last Order Date'      => $last_order_date,
            'Email Notification'  => $user->email_notify ? 'Yes' : 'No',
            'SMS Notification'  => $user->sms_notify ? 'Yes' : 'No',
          ];

        $index++;
      }

      return $transformData;
    }

  /**
   * @return mixed
   */
  public function getKitchenManagers()
    {
      $group = Group::where('name',"Kitchen Manager")->first();
    return User::whereGroupId($group->id)->get();
    }

  /**
   * @param $id
   * @param $input
   * @return bool
   * @throws AssignedToRoleException
   */
  public function checkIfAssigned($id,$input)
    {
      $user = $this->getUserById($id);
      $group = Group::find($input['group_id']);
      //last assigned admin

      if($user->group->name=="Admin" && $group->name !="Admin")
      {
        $admin_count = User::where('group_id',1)->count();
        if($admin_count == 1)
          throw new AssignedToRoleException('Last Admin cannot be assigned to any other role.');
      }
      //kitchen manager is assigned

      else if($user->group->name == "Kitchen Manager" && $group->name !="Kitchen Manager" && $user->manageKitchen) {
        $kitchen_id = $user->manageKitchen;
        if ($kitchen_id != null || $input['group_id']!=3) {
          $kitchen = Kitchen::find($kitchen_id->kitchen_id);
          throw new AssignedToRoleException('This user is assigned to ' . $kitchen->name . ' and cannot be assigned to any other role.');
        }
        return false;
      }
      else
        return false;
    }

  /**
   * @param $user
   * @return mixed
   */
  public function checkIfUserExists($user)
  {
    return $this->getUserByEmail($user['email']);
  }

  /**
   * @param $column
   * @param $value
   * @throws ModelNotFoundException
   */
  public function checkIfUserExistsByColumn($column, $value)
  {
    $user = User::where($column, $value)->first();

    if(!$user) throw new ModelNotFoundException("No User Found !");
  }

  /**
   * @param $user
   * @return static
   */
  public function createUser($user)
  {
    $user = User::create($user);
    $user->update([
        'avatar_url' => 'bueno-default',
        'avatar_mime_type' => 'image/jpeg'
    ]);
    return $user;
  }

  /**
   * @param $inputs
   * @return mixed
   */
  public function createUserOTP($inputs)
  {
    $user = User::wherePhone($inputs['phone'])->first();

    $user->update([
      'mobile_otp' => rand(1000, 9999),
      'otp_created_at' => new \DateTime()
    ]);

    return $user;
  }

  public function checkIfUserOTPExpired($user)
  {
    $now = Carbon::now()->timestamp;

    if((($now - $user->otp_created_at->timestamp)/60) > 5)
    {
      return true;
    }

    return false;
  }

  /**
   * @param $inputs
   * @throws ModelNotFoundException
   */
  public function resetUserPassowrd($inputs)
  {
    $user = User::where('phone', $inputs['phone'])->first();

    if(!$user) throw new ModelNotFoundException('User not Found !');

    if($user->mobile_otp != (Integer) $inputs['code']) throw new ModelNotFoundException('Code is wrong !');

    if($this->checkIfUserOTPExpired($user)) throw new ModelNotFoundException('Sorry. OTP Expired.');

    $user->update([
        'password' => Hash::make($inputs['password']),
        'mobile_verify' => 1
    ]);

  }

  /**
   * @param $inputs
   */
  public function sendForgotPasswordOTP($inputs)
  {
    $user = $this->createUserOTP($inputs);

    switch($inputs['type'])
    {
      case 1:
        $this->sendForgotPasswordOTPBySMS($inputs, $user);
        break;

      case 2:
        $this->sendForgotPasswordOTPByVoice($inputs, $user);
        break;
    }
  }

  public function sendForgotPasswordOTPBySMS($inputs, $user)
  {
    $message = "Your OTP to reset your account password on Bueno is " . $user->mobile_otp;

    $this->sms->send($inputs['phone'], $message );
  }

  public function sendForgotPasswordOTPByVoice($inputs, $user)
  {
    $this->voice->send($inputs['phone'], $user->mobile_otp);
  }

  /**
   * @param $column
   * @param $value
   * @return mixed
   */
  public function getUserByColumn($column, $value)
  {
    return User::where($column, $value)->first();
  }

  /**
   * @param $user
   * @param $confirmation_code
   */
  public function verifyUser($user)
  {
    $user->update([
      'mobile_otp'      => null,
      'mobile_verify'   => 1
    ]);
  }

  /**
   * @param $user
   * @param $data
   * @return mixed
   */
  public function updateUserInfo($user, $data)
  {
    $old_phone = $user->phone;

    $data['sms_notify'] = isset($data['sms_notify']) ? 1 : 0;
    $data['email_notify'] = isset($data['email_notify']) ? 1 : 0;

    $user->update($data);

    if($old_phone != $user->phone)
    {
      $user->update([
          'mobile_otp' => rand(1000, 9999),
          'mobile_verify' => 0
      ]);

      $this->sendVerifyOTPSMS($user->phone, $user->mobile_otp);

    }
    return $user;
  }

  public function sendVerifyOTPSMS($phone, $otp)
  {
      $message = "Your OTP to confirm your account on Bueno is " . $otp;

      $this->sms->send($phone, $message);
  }

  /**
   * @param $user
   * @param $inputs
   * @return static
   */
  public function createUserAddress($user, $inputs)
  {
    if(isset($inputs['is_default']) && $inputs['is_default'] == 1) $this->unsetUserDefaultAddresses($user);

    $inputs['user_id'] = $user->id;

    $address = Address::create($inputs);

    return $address;
  }

  /**
   * @param $user
   */
  public function unsetUserDefaultAddresses($user)
  {
    Address::where('user_id', $user->id)->update([
      'is_default' => 0
    ]);
  }

  /**
   * @param $inputs
   */
  public function updateUserAddress($user, $address_id, $inputs)
  {
    if(isset($inputs['is_default']) && $inputs['is_default'] == 1) $this->unsetUserDefaultAddresses($user);

    $inputs['is_default'] = isset($inputs['is_default']) ? $inputs['is_default'] : 0;

    $address = Address::where('id', $address_id)->where('user_id', $user->id)->first();

    if(!$address) throw new ModelNotFoundException("Address not found !");

    $address->update($inputs);

  }


  /**
   * @param $user_id
   * @return mixed
   */
  public function getUserFavoriteItems($user_id)
  {
    $item_ids = Favorite::where('user_id', $user_id)->get()->pluck('item_id')->toArray();

    return Item::whereIn('id', $item_ids)->get();
  }

  /**
   * @param $user_id
   * @param $item_id
   * @return static
   */
  public function addItemToUserFavorites($user, $item_id)
  {
    if(!$item_id) throw new \Exception('Please specify Item ID');

    $favorite = Favorite::where('user_id',$user->id )->where('item_id', $item_id)->first();

    $item = Item::find($item_id);

    if(!$item) throw new ModelNotFoundException('Item not Found');

    if($favorite) throw new AddToFavoriteException($item->itemable->name . '  already exists in your Favorites !');

     $favorite = Favorite::create([
      'user_id' => $user->id,
      'item_id' => $item_id
    ]);

    return $user->saved_items;
  }

  /**
   * @param $user_id
   * @param $item_id
   * @return mixed
   * @throws ModelCannotBeDeletedException
   */
  public function deleteUserFavorite($user, $item_id)
  {
    $favorite = Favorite::where('user_id', $user->id)->where('item_id', $item_id)->first();

    if(!$favorite) throw new ModelCannotBeDeletedException('This item is already removed.');

    return $favorite->delete();
  }

  public function getAllCartItems($user_id)
  {
    return Cart::where('user_id', $user_id)->get();
  }

  /**
   * @param $user_id
   * @param $inputs
   * @return static
   */
  public function addToUserCart($user, $inputs, $area_id)
  {
    if(!$area_id) throw new AddToCartException('Please select area first');

    $items = Area::find($area_id)->firstKitchen()->kitchenItems->filter(function($item){
      return $item->itemable->status == 1;
    });

    $item = $items->filter(function($item) use ($inputs){
      return $item->id == $inputs['item_id'];
    })->first();

    if(is_null($item)) throw new AddToCartException('This item is disabled.');

    if($item && $item->pivot->value == 0) throw new AddToCartException('Sorry. The item is out of stock.');

    // if no quantity is specified
    if($inputs['quantity'] == '' || $inputs['quantity'] == 0)
    {
      throw new AddToCartException('Please specify quantity');
    }

    // if quantity is more than stock
    if($inputs['quantity'] > $item->pivot->value)
    {
      throw new AddToCartException('You can order only ' . $item->pivot->value . ' ' . $item->itemable->name .' at max right now.');
    }

    // if total cart items + quantity user is adding is greater than 10
    if( $user->cartItems->count() && ( (Integer) $inputs['quantity'] + (Integer) $user->cartItems->sum('quantity')) > 10)
    {
      throw new AddToCartException('You cannot order more than 10 items in your cart.');
    }

    // if quantity is more than 10
    if($inputs['quantity'] > 10)
    {
      throw new AddToCartException('You cannot add more than 10');
    }

    if(!$item) throw new AddToCartException('The item is not available in your area !');

    if($this->checkIfItemAlreadyExistsInCart($user->id, $inputs['item_id']))
    {
      $cart = Cart::where('user_id', $user->id)->where('item_id', $inputs['item_id'])->first();

      $quantity = $cart->quantity + (Integer) $inputs['quantity'];

      if($quantity > $item->pivot->value)
      {
        throw new AddToCartException('You can order only ' . $item->pivot->value . ' ' . $item->itemable->name .' at max right now.');
      };

      if($quantity > 10)
      {
        throw new AddToCartException('You cannot order more than 10');
      };

      $cart->update([
        'quantity' => $quantity
      ]);

      return $user->cartItems()->get();
    }

    $cart = Cart::create([
      'user_id' => $user->id,
      'item_id' => $inputs['item_id'],
      'quantity'  => $inputs['quantity']
    ]);


    $response = Segment::track([
        "userId"     => auth()->user()->id,
        "event"      => "Add to Cart" ,
        "properties" => [
            "quantity" => $inputs['quantity'],
            "item_id"  => $inputs['item_id'],
            "timestamp" => Carbon::now()->timestamp
        ]
    ]);

    LaravelLog::info('Segment .'.$response);

    return $user->cartItems()->get();
  }

  /**
   * @param $user_id
   * @param $item_id
   * @return bool
   */
  public function checkIfItemAlreadyExistsInCart($user_id, $item_id)
  {
    $cart = Cart::where('user_id', $user_id)->where('item_id', $item_id)->first();

    if($cart) return true;

    return false;
  }

  public function deleteCartItem($user, $cart_id)
  {
    $cart = Cart::where('user_id', $user->id)->where('id', $cart_id)->first();

    if(!$cart) throw new ModelNotFoundException('Cart item not found');

    return $cart->delete();
  }

  public function assignGroupToUser($user)
  {
    $user->update([
      'group_id' => 2,
      'status' => 1,
    ]);
  }

  public function assignMembershipToUser($user)
  {
    $membership_id = $this->getUserMembership($user->confirmedOrders()->count());

    $user->update([
      'membership_id' => $membership_id
    ]);
  }

  protected function getUserMembership($order_count)
  {
    $membership_id = 1;

    foreach(Membership::all() as $membership)
    {
      if($order_count >= $membership->min)
      {
        $membership_id = $membership->id;
      }
    }

    return $membership_id;
  }

  public function createUserReferralCode($user)
  {
    $coupon_code = preg_replace("/[^a-z0-9.]+/i", "", strtoupper($user->first_name));
    if(strlen($coupon_code)>4)
    $coupon_code = substr($coupon_code, 0, 4);
    $coupon_code .= $user->id;
    $user->update([
      'referral_code' => $coupon_code
    ]);

    $coupon = Coupon::create([
      'code'  => $user->referral_code,
      'status'  => 0,
      'description' => "Referral Code of newly created user - " . $user->full_name,
      'type' => 1
    ]);

    $check_coupon = CheckCoupon::create([
      'coupon_id' => $coupon->id,
      'check' => 'nth_order',
      'value' => 1,
    ]);

    $check_coupon->update([
      'group' => $check_coupon->id
    ]);

    $coupon_giveaway = CouponGiveaway::create([
      'coupon_id' => $coupon->id,
      'giveaway' => 'discount_value',
      'value' => Setting::where('key', 'referral_points')->first()->value
    ]);

    $coupon_giveaway->update([
      'group' => $coupon_giveaway->id
    ]);

  }

  public function checkIfUserHasAddresses($user)
  {
    $count = $user->addresses->count();

    if(!$count) throw new CheckoutValidationException('Add atleast one address to proceed the checkout');
  }

  public function checkIfUserIsVerified($user)
  {
    $verified = $user->mobile_verify;

    if(!$verified) throw new CheckoutValidationException('Please verify your phone number first to proceed checkout');
  }

  public function setUserArea($inputs)
  {
    session(['area_id' => $inputs['area_id']]);

    return Area::find($inputs['area_id']);
  }

  public function getUserOrders($user_id, $perPage)
  {
    return Order::where('user_id', $user_id)->where('status', '>', 1)->orderBy('created_at', 'DESC')->paginate($perPage);
  }

  public function getUserOrderById($user, $id)
  {
    $order = Order::where('id', $id)->where('user_id', $user->id)->first();

    if(!$order) throw new ModelNotFoundException('No Order Found !');

    return $order;
  }

  public function checkIfUserHasAddressesInArea($user)
  {
    $count = $user->addresses->where('area_id', session('area_id'))->count();

    if(!$count) throw new CheckoutValidationException('Add atleast one address to proceed the checkout');
  }

  public function addItemsToCart($item_array)
  {
    foreach($item_array as $key => $inputs) {

      $items = Area::find(session('area_id'))->firstKitchen()->kitchenItems->filter(function ($item) {
        return $item->pivot->value > 0;
      });

      $item = $items->filter(function ($item) use ($inputs) {
        return $item->id == $inputs['item_id'];
      })->first();

      // if no quantity is specified
      if ($inputs['quantity'] == '' || $inputs['quantity'] == 0) {
        throw new AddToCartException('Please specify quantity');
      }

      // if quantity is more than 10
      if ($inputs['quantity'] > 10) {
        throw new AddToCartException('You cannot add more than 10');
      }

      // if total cart items + quantity user is adding is greater than 10
      if( auth()->user()->cartItems->count() && ( (Integer) $inputs['quantity'] + (Integer) auth()->user()->cartItems->sum('quantity')) > 10)
      {
        throw new AddToCartException('You cannot order more than 10 items in your cart.');
      }


      if (!$item) throw new AddToCartException('The item is not available in your area !');

      if ($this->checkIfItemAlreadyExistsInCart(auth()->user()->id, $inputs['item_id'])) {
        $cart = Cart::where('user_id', auth()->user()->id)->where('item_id', $inputs['item_id'])->first();

        $quantity = $cart->quantity + (Integer)$inputs['quantity'];

        if ($quantity > $item->pivot->value) {
          throw new AddToCartException('You can order only ' . $item->pivot->value . ' ' . $item->itemable->name . ' at max right now.');
        };

        $cart->update([
            'quantity' => $quantity
        ]);
      }else{
        Cart::create([
          'quantity'  => 1,
          'item_id'   => $inputs['item_id'],
          'user_id'   => auth()->user()->id
        ]);
      }
    }

    return auth()->user()->cartItems()->get();

  }

  /**
   * first checks if old password is same new old password,
   * then validates and updates the password
   *
   * @param $inputs
   * @param $user
   * @return Client @client
   * @throws Exception
   */
  public function updatePassword($inputs,$user)
  {
    if( $inputs['new_password'] == '' || $inputs['new_password_confirm'] == '')
    {
      throw new UpdatePasswordException('Please fill all fields');
    }

    if(!Hash::check($inputs['old_password'], $user->password))
    {
      throw new UpdatePasswordException('Old Password entered is wrong');
    }

    if($inputs['new_password'] !== $inputs['new_password_confirm'])
    {
      throw new UpdatePasswordException('confirm password is wrong');
    }

    $client = User::find($user->id)->update([
        'password' => Hash::make($inputs['new_password'])
    ]);

    return $client;
  }

  public function resendOTPCode($user)
  {
    $user->update([
      'otp_created_at' => new \DateTime(),
      'mobile_otp'    => rand(1000, 9999)
    ]);

    $this->sendVerifyOTPSMS($user->phone, $user->mobile_otp);
  }

  public function saveCartQuantity($cart_id, $quantity)
  {
    $cart = Cart::find($cart_id);

    $cart->update([
      'quantity'  => $quantity
    ]);
  }

  public function checkIfUserIsBlackListed($user)
  {
    $is_blacklisted = $user->status == 0 ? true : false;

    if($is_blacklisted) throw new CheckoutValidationException('Sorry. You are not allowed to order.');
  }

  public function resendForgotPasswordOTP($inputs)
  {
    $phone = isset($inputs['phone']) ? $inputs['phone'] : null;

    if(!$phone) throw new ValidationException('Phone Number is missing');

    $user = $this->getUserByPhone($phone);

    if(!$user) throw new ModelNotFoundException('No User Found !');

    $this->resendOTPCode($user);
  }

  public function getUsersListing($inputs)
  {
    $keyword = isset($inputs['keyword']) ? $inputs['keyword'] : '';
    $sortBy = isset($inputs['sortBy']) ? $inputs['sortBy'] : '';
    $direction = isset($inputs['direction']) && $inputs['direction'] == 'asc' ? "asc" : "desc" ;


    $memberships = new Membership;

    $memberships = $memberships
        ->where('name','LIKE','%'.$keyword.'%')->take(10)->get();

    $membership_ids = $memberships->pluck('id')->toArray();


    $groups = new Group;

    $groups = $groups
        ->where('name','LIKE','%'.$keyword.'%')->take(10)->get();

    $group_ids = $groups->pluck('id')->toArray();

    $users = new User;

    if(count($membership_ids))
    {
      $users = $users->whereIn('membership_id', $membership_ids);
    }

    if(count($group_ids))
    {
      $users = $users->whereIn('group_id', $group_ids);
    }

    $users = $users->orWhere('id', 'LIKE', '%' . $keyword . '%');

    $users = $users->orwhereRaw("(CONCAT(first_name,' ',last_name) like '%".$keyword."%')");

    $users = $users->orWhere('email', 'LIKE', $keyword );

    $users = $users->orWhere('phone', 'LIKE', $keyword );

    $users = $this->sortUsers($users, $sortBy, $direction);

    return $users->paginate(10);
  }

      /**
   * @param $orders
   * @param $sortBy
   * @param $direction
   * @return mixed
   */
  public function sortUsers($users, $sortBy, $direction)
  {

    switch ($sortBy) {

        case 'id' :
          $users = $users->orderBy('id', $direction);
           break;

        case 'name' :
          $users = $users->orderBy('first_name', $direction);
           break;

        case 'group' :
          $users = $users->orderBy('group_id', $direction);
           break;

        case 'last_login' :
          $users = $users->orderBy('last_login_at', $direction);
           break;
       
        default:
          $users = $users->orderBy('created_at', $direction );
          break;
      }

      return $users;
  }

  public function checkIfAddressExists($inputs, $user)
  {

    $address = Address::where('user_id', $user->id)->where('id', $inputs['address_id'])->first();

    if(!$address) throw new CheckoutValidationException('Sorry. The address selected is invalid');
  }

  public function checkIfUserCartHasDisabledItems($cart_items)
  {
    foreach($cart_items as $cart_item)
    {
      if($cart_item->item->itemable->status == 0 || $cart_item->item->itemable->status == 2)
      {
        throw new CheckoutValidationException('Sorry ! You are trying to order an item which is not for sale right now.');
      } 
    }
  }

  public function checkIfPaymentSelectedIsEnabled($inputs)
  {
    $payment_mode = PaymentMode::where('id', $inputs['payment_mode_id'])->where('status', 1)->first();

    if(!$payment_mode) throw new CheckoutValidationException('Sorry ! The Payment mode you selected is disabled.');
  }

  public function optOutUserFromSMS($phone)
  {
    $user = $this->getUserByPhone($phone);

    if(!$user) throw new ModelNotFoundException('No User Found');

    $user->update([
      'sms_notify' => 0
    ]);
  }

  public function saveUserFeedbackOnMissedCall($inputs)
  {
    $caller_number = $inputs['From'];
    if(strlen($caller_number)>10)
        $caller_number = substr($caller_number, -10);
    $user = $this->getUserByPhone($caller_number);

    if(!$user) return;

    $last_order = Order::where('user_id', $user->id)->where('status', 4)->orderBy('created_at', 'DESC')->limit(1)->first();

    $feedback_number = $inputs['To'];
    if(strlen($feedback_number)>11)
        $feedback_number = substr($feedback_number, -11);

    if ($last_order) {
      // happy
      if ($feedback_number == '01130018136') {
        $last_order->update([
            'feedback_on_missed_call' => 'happy',
            'feedback_missed_call_time' => $inputs['CurrentTime']
        ]);
      }

      //sad
      if ($feedback_number == '01139595965') {
        $last_order->update([
            'feedback_on_missed_call' => 'sad',
            'feedback_missed_call_time' => $inputs['CurrentTime']
        ]);
      }
    }
  }

  public function updateUserCouponStatus()
  {
    User::chunk(500,function($users){
      foreach ($users as $user) {
       $ref_code = Coupon::where('code',$user->referral_code)->first();
       if($ref_code)
       {
        if($user->confirmedOrders()->count()>0)
              {
               $ref_code->status=1;
              }
              else
               {
                 $ref_code->status=0;
               }
               $ref_code->save();
               LaravelLog::info('User ID: '.$user->id.' Coupon : '.$ref_code->code.' Status : '.$ref_code->status);
             }
             else
              LaravelLog::info('User ID: '.$user->id.' Coupon : '.$user->referral_code.' Status : Coupon Not Found');
      }
    });
  }
}
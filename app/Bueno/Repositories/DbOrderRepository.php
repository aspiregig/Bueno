<?php namespace Bueno\Repositories;

use App\Events\OrderStatusWasUpdated;
use App\Events\OrderWasCreatedByUser;
use App\Events\OrderWasDispatched;
use App\Events\OrderWasSettled;
use App\Events\UserWasCreated;
use App\Events\ReferralSuccess;
use App\Models\DeliveryBoy;
use App\Models\Ngo;
use DB;
use App\Models\Order;
use App\Models\JoolehLog;
use App\Models\Membership;
use App\Models\User;
use App\Models\Address;
use App\Models\OrderSource;
use App\Models\PaymentMode;
use App\Models\Payment;
use App\Models\MealsOrder;
use App\Models\PaymentStatus;
use App\Models\CombosOrder;
use App\Models\Meal;
use App\Models\Area;
use App\Models\Combo;
use App\Models\Cuisine;
use App\Models\Category;
use App\Models\Kitchen;
use App\Models\OrderStatus;
use App\Models\LogOrder;
use App\Models\ItemOrder;
use App\Models\CheckCoupon;
use App\Models\Coupon;
use App\Models\CouponGiveAway;
use App\Models\Item;
use App\Models\Group;
use App\Models\Setting;
use App\Models\Stock;
use App\Models\CancelReason;
use App\Models\OrderInvoice;
use Bueno\Exceptions\CheckoutValidationException;
use Bueno\Exceptions\InvalidCouponException;
use Bueno\Exceptions\ModelNotFoundException;
use Bueno\Loggers\JoolehLogger;
use Bueno\Loggers\LoyalityLogger;
use Carbon\Carbon;
use DateTime;
use Segment;
use DateInterval;
use Illuminate\Support\Facades\Log;
use Mockery\Exception\InvalidCountException;
use Bueno\Loggers\DebugLogger;
use Log as LaravelLog;

class DbOrderRepository
{
  protected $order,$payment_info,$meals,$amount;


  //Types Of Orders

  public function getAllOrders()
  {
    return Order::get();
  }

  public function getTotalOrders()
  {
    return Order::paginate(10);
  }

  public function getOrdersByStatus($status)
  {
    return Order::where('status',$status)->get();
  }

  public  function getOrders($number)
  {
    return Order::orderBy('created_at','DESC')->paginate($number);
  }
  public function getAllOrdersByDates($start,$end,$kitchen_id="all")
  {
    if($kitchen_id=="all" || $kitchen_id=='-1')
    return Order::where('created_at','>=',$start)->where('created_at','<=',$end)->get();
    else
    return Order::where('kitchen_id',$kitchen_id)->where('created_at','>=',$start)->where('created_at','<=',$end)->get();
  }

  public function getOrderById($id)
  {
    return Order::find($id);
  }


  public function getAllOrdersByDays($days,$kitchen_id="all")
  {
    $period = '-'.$days.' days';
    $today = new DateTime('00:00:00');
    if($kitchen_id=="all")
    return Order::where('created_at','>',$today->modify($period))->get();
    else
    return Order::where('kitchen_id',$kitchen_id)->where('created_at','>',$today->modify($period))->get();
  }


  public function getAllOrdersByMonths($months)
  {
    $period = '-'.$months.' months';
    $today = new DateTime();
    return Order::where('updated_at','>',$today->modify('last day of previous month'));
  }

  public function getAllOrderStatus()
  {
    return OrderStatus::get();
  }


  public function getAllOrderSources()
  {
    return OrderSource::get();
  }

  public function getAllOrderSourcesBueno()
  {
    return OrderSource::where('name','Bueno Web')->get();
  }

  public function getAllPaymentModes()
  {
    return PaymentMode::get();
  }

  public function getAllPaymentModesCOD()
  {
    return PaymentMode::where('name','COD')->orWhere('name','Others')->get();
  }

  public function getAllOrderCancelReasons()
  {
    return CancelReason::get();
  }

  public function checkStock($meals,$combos,$area_id)
  {
      $check_meals = $meals;
      $check_combos = $combos;

        $area = Area::find($area_id);
        $kitchen = $area->kitchen->first();
        if($check_meals)
        foreach($check_meals as $check_meal) {
          $item = Item::where('itemable_type', 'App\Models\Meal')->where('itemable_id', $check_meal['id'])->first();
          $stock = Stock::where('kitchen_id', $kitchen->id)->where('item_id', $item->id)->first();
          if ($stock)
          {if ($check_meal['quantity'] > $stock->value) {
              return false;
            }}
          else
            return false;
        }
    if($check_combos)
      foreach($check_combos as $check_combo)
          {
            $item = Item::where('itemable_type','App\Models\Combo')->where('itemable_id',$check_combo['id'])->first();
            $stock = Stock::where('kitchen_id',$kitchen->id)->where('item_id',$item->id)->first();
            if($stock)
            {if($check_combo['quantity'] >$stock->value)
              {
                return false;
              }}
            else
              return false;
          }
    return true;
  }

  public function addSalesAmount($payments)
  {
    return $payments->sum('amount');
  }

  public function checkAutoApplyCoupon($user_id,$old_order=1)
  {
      $user = User::find($user_id);
      $order_count = $user->orders->count();
      $order_count+=$old_order;
      $auto_coupons = CheckCoupon::where('check','auto_apply')->get();
      foreach ($auto_coupons as $auto_coupon) {
        $nth_check = CheckCoupon::where('group',$auto_coupon->group)->where('check','nth_order')->first();
        if($order_count==$nth_check->value)
        {
          return $auto_coupon->coupon_id;
        }
      }
      return null;
  }

  public function getUserAddress($user_id,$address)
  {
    return Address::whereUserId($user_id)->whereAddress($address)->first();
  }

  public function getUserByPhone($phone)
  {
    return User::wherePhone($phone)->first();
  }

  public function newUserAddress($user_id,$new_address)
  {
    $address = new Address;
    $address->user_id = $user_id;
    $address->area_id = $new_address['area_id'];
    $address->address = $new_address['address'];
    $address->save();
    return $address;
  }
  public function checkCouponApi($inputs)
  {
    $phone = $inputs['phone'];
    $area_id = $inputs['area_id'];
    $coupon_code = $inputs['coupon_code'];
      $data['description'] = "";
    $coupon = Coupon::where('code', $coupon_code)->get()->first();

    if($coupon==null)
    {
      $data['message'] = 'No Such Coupon Exist';
      $data['code'] = '1';
      $data['message_type'] = 'error';
      $data['discount'] = '0';
      $data['success'] = '0';
      return $data;
    }

    $data['items'] = null;

    if(is_null($area_id))
    {
      $area_id = 0;
    }
    $user = $this->getUserByPhone($phone);

    if($user==null)
    {
      $user_id = 0;
    }
    else
    {
      $user_id = $user->id;
    }
    if($user_id!=0)
    {
      if($user->group->name!="User")
      {
        $data['message'] = 'Order Cannot be placed for non Users.';
        $data['code'] = '0';
        $data['message_type'] = 'error';
        $data['discount'] = '0';
        $data['success'] = '0';
        return $data;
      }
    }

    $validity = $this->couponValidaityApi($user_id,$area_id,$inputs,$coupon);
    if($validity['success']==1)
    {
      $coupon_response = $this->couponGive($coupon,$validity);

      $data['message'] = 'Coupon Applicable.' . $validity['message'].'Discount Applicable on '.$validity['discount_applicable_on'];
      $data['code'] = '3';
      $data['message_type'] = 'success';
      $data['discount'] = $coupon_response['discount'];
      $data['items'] = $coupon_response['items'];
      $data['cashback'] = $coupon_response['cashback'];
    }
    else
    {
      $data['message'] = 'Coupon Not Applicable. Details : '. $validity['message'];
      $data['code'] = '0';
      $data['message_type'] = 'error';
      $data['discount'] = '0';
      $data['cashback'] = '0';
    }
    $data['success']=$validity['success'];

    if($data['discount']>0)
    {
      $data['discount_type'] = 'cash';
      $data['description'] = "";
      if($data['cashback']>0 )
      {
        $data['discount_type'] = 'hybrid';
      }
      if( $data['items'])
      {
        $data['discount_type'] = 'hybrid';
        $data['description'] = $coupon->description;
      }
    }
    else if($data['cashback']>0)
    {
      $data['discount_type'] ='credit';
      if($data['items'])
      {
        $data['discount_type'] = 'hybrid';
        $data['description'] = $coupon->description;
      }
    }
    else if($data['items'])
    {
      foreach ($data['items'] as $item) {
        if($item['type']=='Goody')
        {
        $data['discount_type'] ='freebie';
        }
        else
        {
        $data['discount_type'] ='meal';
        }
      }
      $data['description'] = $coupon->description;
    }
    return $data;
  }
    public function checkCoupon($request)
  {
      $inputs = $request->all();

      $coupon = Coupon::where('code', $inputs['coupon_code'])->get()->first();

      if($coupon==null)
      {
        $data['message'] = 'No Such Coupon Exist';
        $data['code'] = '1';
        $data['message_type'] = 'error';
        $data['discount'] = '0';
        $data['success'] = '0';
        return $data;
      }

      $user = $this->getUserByPhone($inputs['phone']);

      if($user==null)
        {
              $user_id = 0;
        }
    else
    {
      $user_id = $user->id;
    }
        if($user_id!=0)
        {
          if($user->group->name!="User")
          {
            $data['message'] = 'Order Cannot be placed for non Users.';
            $data['code'] = '0';
            $data['message_type'] = 'error';
            $data['discount'] = '0';
            $data['success'] = '0';
            return $data;
          }
        }
    $area_id = isset($inputs['area_id']) ? $inputs['area_id'] : 0;
     $validity = $this->couponValidaity($user_id,$area_id,$request,$coupon);
     if($validity['success']==1)
     {
        $coupon_response = $this->couponGive($coupon,$validity);

        $data['message'] = 'Coupon Applicable.' . $validity['message'];
        $data['code'] = '3';
        $data['message_type'] = 'success';
        $data['discount'] = $coupon_response['discount'];
        $data['items'] = $coupon_response['items'];
        $data['cashback'] = $coupon_response['cashback'];
     }
    else
    {
      $data['message'] = 'Coupon Not Applicable. Details : '. $validity['message'];
      $data['code'] = '0';
      $data['message_type'] = 'error';
      $data['discount'] = '0';
      $data['cashback'] = '0';
    }
      $data['success']=$validity['success'];
     return $data;
  }

  public function couponValidaityApi($user_id,$area_id,$inputs,$coupon)
  {
    $logger = new DebugLogger;
    $items = null;
    $discount_applicable_on = 0;
    $max_discount_amount = 0;
    $max_cashback_amount = 0;
    $checks = $coupon->checks;

    $items_checks = $inputs['items'];

    if($items_checks==null)
      $items_checks = $items;
    elseif($items !=null)
      $items_checks = array_merge($items,$items_checks);

    $pg_id = $inputs['payment_mode_id'];

    $coupon_user = User::find($user_id);

    //Coupon Active Status
    if($coupon->status==0)
    {
      $data['success'] = 0;
      $data['message'] = 'Coupon is Either Expired or Inactive';
      $data['discount_applicable_on'] = 0;
      $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
      return $data;
    }

    $kitchen_check = 0;
    $login_user = null;
    $area = Area::find($area_id);
    if($area)
    {
      $area_id = $area->id;
    }
    else
    {
      $area_id = 0;
    }
    $order_count = 0;
    $amount = 0;
    if($items_checks)
      foreach ($items_checks as $item) {
        $new_item = Item::find($item['id']);
        $amount +=$new_item->itemable->discount_price * $item['quantity'];
        $order_count +=$item['quantity'];
      }
    $data['amount'] = $amount;
    foreach($checks as $check)
    {
      switch ($check->check) {
        case 'from':
          $current = new DateTime();
          $check_date = new DateTime($check->value);
          if($current<=$check_date)
          {
            $data['success'] = 0;
            $data['message'] = 'Coupon is Valid from'.$check_date->format('d F, Y');
            $data['discount_applicable_on'] = 0;
            $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
            return $data;
          }
          break;
        case 'to':
          $current = new DateTime();
          $check_date = new DateTime($check->value);
          if($current>=$check_date)
          {
            $data['success'] = 0;
            $data['message'] = 'Coupon has been Expired Since'.$check_date->format('d F Y H:i:s');
            $data['discount_applicable_on'] = 0;
            $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
            return $data;
          }
          break;
        case 'min_order_amount':
          if($check->value>$amount)
          {
            $data['success'] = 0;
            $data['message'] = 'Minimum Order Amount is '.$check->value;
            $data['discount_applicable_on'] = 0;
            $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
            return $data;
          }
          break;
          case 'max_discount_amount':
            $max_discount_amount = $check->value;
            break;
          case 'max_cashback_amount':
            $max_cashback_amount = $check->value;
            break;
        case 'total_min_quantity':
          if($check->value>=$order_count)
            {
              $data['success'] = 0;
              $data['message'] = 'Minimum no of items in order must be '.$check->value;
              $data['discount_applicable_on'] = 0;
              $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
              return $data;
            }
          break;
        case 'one_time':
          $orders = Order::where('coupon_id',$coupon->id)->get();
          $orders = $orders->filter(function($order){
            return $order->paymentInfo->status==3 ? true : false;
          });
          if($orders->count()>0)
          {
            $data['success'] = 0;
            $data['message'] = 'Coupon has been Expired.';
            $data['discount_applicable_on'] = 0;
            $data['max_discount_amount'] = 0;
            $data['max_cashback_amount'] = 0;
            return $data;
          }
          break;
        case 'nth_order':
          switch($check->value %10)
          {
            case 1 : $number_ending = "st"; break;
            case 2 : $number_ending = "nd"; break;
            case 3 : $number_ending = "rd"; break;
            default : $number_ending = "th"; break;
          }
          if($coupon_user==null)
          {
            if($check->value !=1)
            {
              $data['success'] = 0;
              $data['message'] = 'This Coupon is applicable on '.$check->value.$number_ending.' Order.';
              $data['discount_applicable_on'] = 0;
              $data['max_discount_amount'] = 0;
              $data['max_cashback_amount'] = 0;
              return $data;
            }
          }
          else if($check->value!=($coupon_user->nonCancelledOrders()->count()+1))
          {
            $data['success'] = 0;
            $data['message'] = 'This Coupon is applicable on '.$check->value.$number_ending.' Order.';
            $data['discount_applicable_on'] = 0;
            $data['max_discount_amount'] = 0;
            $data['max_cashback_amount'] = 0;
            return $data;
          }
          break;
        case 'source':
          $platform = $inputs['source_id'];
          
            $all_platforms = CheckCoupon::where('coupon_id',$check->coupon_id)->where('check','source')->get();
          $all_platforms = $all_platforms->pluck('value')->toArray();
          if(!in_array($platform,$all_platforms))
          {
            $data['success'] = 0;
           $data['success'] = 0;
            if(in_array(1,$all_platforms) && in_array(4,$all_platforms))
            $data['message'] = 'Coupon is valid only on our Apps';
            else if(in_array(1,$all_platforms))
            $data['message'] = 'Coupon is valid only on our Android App Only';
            else if(in_array(4,$all_platforms))
            $data['message'] = 'Coupon is valid only on our IOS App Only';
            else
            $data['message'] = 'This Coupon is platform specific';
            $data['discount_applicable_on'] = 0;
            $data['max_discount_amount'] = 0;
            $data['max_cashback_amount'] = 0;
            return $data;
          }
        
          break;
        case 'on_payment_gateway':
          $payment_gateways = CheckCoupon::where('coupon_id',$check->coupon_id)->where('check','on_payment_gateway')->get();
          $pg_ids = $payment_gateways->pluck('value')->toArray();
          if(!in_array($pg_id, $pg_ids))
          {
            {
              $data['success'] = 0;
              $data['message'] = 'This Coupon is applicable on some Payment Gateways Only.';
              $data['discount_applicable_on'] = 0;
              $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
              return $data;
            }
          }
          break;
        case 'on_kitchen':
          if($kitchen_check == 0 ) {
            $kitchen_checks = CheckCoupon::where('coupon_id', $check->coupon_id)->where('check', 'on_kitchen')->get();
            $kitchen_ids = $kitchen_checks->pluck('value')->toArray();
            $area_ids = [];
            foreach ($kitchen_ids as $kid) {
              $kitchen = Kitchen::find($kid);
              $area_ids += $kitchen->areas->pluck('id')->toArray();
            }
            $logger->log(json_encode($area_ids));
            if (!in_array($area_id, $area_ids)) {
                $data['success'] = 0;
                $data['message'] = 'This Coupon is applicable on some Kitchens Only.';
                $data['discount_applicable_on'] = 0;
                $data['max_discount_amount'] = 0;
                $data['max_cashback_amount'] = 0;
                return $data;
            }
            else
            $kitchen_check = 1;
          }
          break;
        case 'for_user':
          $users = CheckCoupon::where('coupon_id',$check->coupon_id)->where('check','for_user')->get();
          $user_ids = $users->pluck('value')->toArray();
          if(!in_array($user_id, $user_ids))
          {
            $data['success'] = 0;
            $data['message'] = 'This Coupon is applicable on some users Only.';
            $data['discount_applicable_on'] = 0;
            $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
            return $data;
          }
          break;
        case 'by_user':
          $admins = CheckCoupon::where('coupon_id',$check->coupon_id)->where('check','by_user')->get();
          $admin_ids = $admins->pluck('value')->toArray();
          if(!in_array($login_user->id, $admin_ids))
          {
            {
              $data['success'] = 0;
              $data['message'] = 'This Coupon can be applied by some admins Only.';
              $data['discount_applicable_on'] = 0;
              $data['max_discount_amount'] = 0;
              $data['max_cashback_amount'] = 0;
              return $data;
            }
          }
          break;
        case 'on_area':
          $areas = CheckCoupon::where('coupon_id',$check->coupon_id)->where('check','on_area')->get();
          $area_ids = $areas->pluck('value')->toArray();
          if(!in_array($area_id, $area_ids))
          {
            {
              $data['success'] = 0;
              $data['message'] = 'This Coupon is applicable on some areas Only.';
              $data['discount_applicable_on'] = 0;
              $data['max_discount_amount'] = 0;
              $data['max_cashback_amount'] = 0;
              return $data;
            }
          }
          break;
        case 'on_state':
          $states = CheckCoupon::where('coupon_id',$check->coupon_id)->where('check','on_state')->get();
          $state_ids = $states->pluck('value')->toArray();
          if($area_id==0)
          $state_id = 0;
          else
            $state_id = $area->city->state->id;
          if(!in_array($state_id, $state_ids))
          {
            {
              $data['success'] = 0;
              $data['message'] = 'This Coupon is applicable on some states Only.';
              $data['discount_applicable_on'] = 0;
              $data['max_discount_amount'] = 0;
              $data['max_cashback_amount'] = 0;
              return $data;
            }
          }
          break;
        case 'on_city':
          $cities = CheckCoupon::where('coupon_id',$check->coupon_id)->where('check','on_city')->get();
          $city_ids = $cities->pluck('value')->toArray();
          if($area_id==0)
            $city_id = 0;
          else
            $city_id = $area->city->id;
          if(!in_array($city_id, $city_ids))
          {
            {
              $data['success'] = 0;
              $data['message'] = 'This Coupon is applicable on some cities Only.';
              $data['discount_applicable_on'] = 0;
              $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
              return $data;
            }
          }
          break;
        case 'cuisine':
          $cuisine = Cuisine::find($check->value);
          $dependency = CheckCoupon::where('check','quantity')->where('group',$check->group)->first();
          $quantity = $dependency->value;
          $meals = $inputs['items'];
          foreach ($meals as $meal) {
            $check_meal = Item::find($meal['id']);
              if($cuisine->id==$check_meal->itemable->cuisine->id&&$check_meal->itemable->status==1)
              {
                $meal_quantity = $quantity - $meal['quantity'];
                if($meal_quantity<=0) {
                  $discount_applicable_on += $check_meal->itemable->discount_price * $quantity;
                  $quantity-=$meal['quantity'];
                  break;
                }else {
                  $quantity-=$meal['quantity'];
                  $discount_applicable_on += $check_meal->itemable->discount_price * $quantity;
                }
              }
          }

          if($quantity>0)
          {
            {
              $data['success'] = 0;
              $data['message'] = 'This Coupon is applicable on '.$cuisine->name.'.Add '.$quantity.'More';
              $data['discount_applicable_on'] = 0;
              $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
              return $data;
            }
          }
          break;
        case 'category' :
          $category = Category::find($check->value);
          $dependency = CheckCoupon::where('check','quantity')->where('group',$check->group)->first();
          $quantity = $dependency->value;
          $meals = $inputs['items'];
          foreach ($meals as $meal) {
            $check_meal = Item::find($meal['id']);
            if($category->id==$check_meal->itemable->category->id&&$check_meal->itemable->status==1)
            {
              $meal_quantity = $quantity - $meal['quantity'];
              if($meal_quantity<=0) {
                $discount_applicable_on += $check_meal->itemable->discount_price * $quantity;
                $quantity-=$meal['quantity'];
                break;
              }else {
                $quantity-=$meal['quantity'];
                $discount_applicable_on += $check_meal->itemable->discount_price * $quantity;
              }
            }
          }
          if($quantity>0)
          {
            $data['success'] = 0;
            $data['message'] = 'This Coupon is applicable on '.$category->name;
            $data['discount_applicable_on'] = 0;
            $data['max_discount_amount'] = 0;
            $data['max_cashback_amount'] = 0;
            return $data;
          }
          break;
        case 'meal':
          $check_meal = Meal::find($check->value);
          $dependency = CheckCoupon::where('check','quantity')->where('group',$check->group)->first();
          $quantity = $dependency->value;
          if($meals)
          {
            foreach($meals as $meal)
            {
              $test_meal = Meal::find($meal['id']);
              $meal_quantity = $meal['quantity'];
              if($test_meal->id == $check->value&&$test_meal->status==1)
              {
                $quantity-=$meal_quantity;
              }
            }
          }
          if($quantity>0)
          {
            $data['success'] = 0;
            $data['message'] = 'This Coupon is applicable on '.$check_meal->name.' - '.$quantity;
            $data['discount_applicable_on'] = 0;
            $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
            return $data;
          }
          break;
        case 'item':
          $item = Item::find($check->value);
          $dependency = CheckCoupon::where('check','quantity')->where('group',$check->group)->first();
          $quantity = $dependency->value;
          $items = $inputs['items'];
          if($items!=null)
            foreach ($items as $cart_item) {
              $check_item = Item::find($cart_item['id']);
              if($item->id==$check_item->id&&$check_meal->itemable->status==1)
              {
                $item_quantity = $quantity - $cart_item['quantity'];
                if($item_quantity<=0) {
                  $discount_applicable_on += $check_item->itemable->discount_price * $quantity;
                  $quantity-=$cart_item['quantity'];
                  break;
                }else {
                  $quantity-=$cart_item['quantity'];
                  $discount_applicable_on += $check_item->itemable->discount_price * $quantity;
                }
              }
            }
          if($quantity>0)
          {
            $data['success'] = 0;
            $data['message'] = 'This Coupon is applicable on '.$item->itemable->name.'. Add '.$quantity.'More';
            $data['discount_applicable_on'] = 0;
            $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
            return $data;
          }
          break;

        default:

          break;
      }
    }

    if($discount_applicable_on==0)
    {
      $discount_applicable_on = $amount;
    }

    $data['success'] = 1;
    $data['message'] = 'Coupon Applied Successfully';
    $data['discount_applicable_on'] = $discount_applicable_on;
    $data['max_discount_amount'] = $max_discount_amount;
    $data['max_cashback_amount'] = $max_cashback_amount;
    return $data;
  }


  public function couponValidaity($user_id,$area_id,$request,$coupon)
  {
    $logger = new DebugLogger;
    $inputs = $request->all();
    $logger->log(json_encode($inputs));
    $items = null;
    $discount_applicable_on = 0;
    $max_discount_amount = -1;
    $max_cashback_amount = -1;
    $checks = $coupon->checks;
    $meals = isset($inputs['meals']) ? $inputs['meals'] : null;
    $combos = isset($inputs['combos']) ? $inputs['combos'] : null;
    $item_counter = 0;
    if($meals) {
      foreach ($meals as $meal) {
        $item = Item::where('itemable_id', $meal['id'])->where('itemable_type', 'App\Models\Meal')->get()->first();
        $items[$item_counter]['id'] = $item->id;
        $items[$item_counter]['quantity'] = $meal['quantity'];
        $item_counter++;
      }
    }

    if($combos) {
      foreach ($combos as $combo) {
        $item = Item::where('itemable_id', $combo['id'])->where('itemable_type', 'App\Models\Combo')->get()->first();
        $items[$item_counter]['id'] = $item->id;
        $items[$item_counter]['quantity'] = $combo['quantity'];
        $item_counter++;
      }
    }
    $items_checks = isset($inputs['items']) ? $inputs['items'] : null;;
    if($items_checks==null)
      $items_checks = $items;
      elseif($items !=null)
    $items_checks = array_merge($items,$items_checks);
    $pg_id = $inputs['payment_mode_id'];
    $coupon_user = User::find($user_id);

    //Coupon Active Status
    if($coupon->status==0)
    {
      $data['success'] = 0;
      $data['message'] = 'Coupon is Either Expired or Inactive';
      $data['discount_applicable_on'] = 0;
      $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
      return $data;
    }

    $kitchen_check = 0;
    $login_user = auth()->user();
    $area = Area::find($area_id);
    $order_count = 0;
    $amount = 0;
    if($items_checks)
    foreach ($items_checks as $item) {
      $new_item = Item::find($item['id']);
      $amount +=$new_item->itemable->discount_price * $item['quantity'];
      $order_count +=$item['quantity'];
    }
    $data['amount'] = $amount;
    foreach($checks as $check)
    {
        switch ($check->check) {
          case 'from':
            $current = new DateTime();
            $check_date = new DateTime($check->value);
            if($current<=$check_date)
            {
              $data['success'] = 0;
              $data['message'] = 'Coupon is Valid from'.$check_date->format('d F, Y');
              $data['discount_applicable_on'] = 0;
              $data['max_discount_amount'] = 0;
              $data['max_cashback_amount'] = 0;
              return $data;
            }
            break;
          case 'to':
            $current = new DateTime();
            $check_date = new DateTime($check->value);
            if($current>=$check_date)
            {
              $data['success'] = 0;
              $data['message'] = 'Coupon has been Expired Since'.$check_date->format('d F Y H:i:s');
              $data['discount_applicable_on'] = 0;
              $data['max_discount_amount'] = 0;
              $data['max_cashback_amount'] = 0;
              return $data;
            }
            break;
          case 'min_order_amount':
              if($check->value>$amount)
              {
                $data['success'] = 0;
                $data['message'] = 'Minimum Order Amount is '.$check->value;
                $data['discount_applicable_on'] = 0;
                $data['max_discount_amount'] = 0;
                $data['max_cashback_amount'] = 0;
                return $data;
              }
            break;
          case 'max_discount_amount':
            $max_discount_amount = $check->value;
            break;
          case 'max_cashback_amount':
            $max_cashback_amount = $check->value;
            break;
          case 'total_min_quantity':
              if($check->value>$order_count)
                {
                  $data['success'] = 0;
                  $data['message'] = 'Minimum no of items in order must be '.$check->value;
                  $data['discount_applicable_on'] = 0;
                  $data['max_discount_amount'] = 0;
                  $data['max_cashback_amount'] = 0;
                  return $data;
                }
            break;
          case 'one_time':
              $orders = Order::where('coupon_id',$coupon->id)->get();
              $orders = $orders->filter(function($order){
                return $order->paymentInfo->status==3 ? true : false;
              });
              if($orders->count()>0)
              {
                $data['success'] = 0;
                $data['message'] = 'Coupon has been Expired.';
                $data['discount_applicable_on'] = 0;
                $data['max_discount_amount'] = 0;
                $data['max_cashback_amount'] = 0;
                return $data;
              }
            break;
          case 'nth_order':
            switch($check->value %10)
          {
            case 1 : $number_ending = "st"; break;
            case 2 : $number_ending = "nd"; break;
            case 3 : $number_ending = "rd"; break;
            default : $number_ending = "th"; break;
          }
          if($coupon_user==null)
          {
            if($check->value !=1)
            {
              $data['success'] = 0;
              $data['message'] = 'This Coupon is applicable on '.$check->value.$number_ending.' Order.';
              $data['discount_applicable_on'] = 0;
              $data['max_discount_amount'] = 0;
              $data['max_cashback_amount'] = 0;
              return $data;
            }
          }
          else if($check->value!=($coupon_user->nonCancelledOrders()->count()+1))
          {
            $data['success'] = 0;
            $data['message'] = 'This Coupon is applicable on '.$check->value.$number_ending.' Order. ';
            $data['discount_applicable_on'] = 0;
            $data['max_discount_amount'] = 0;
            $data['max_cashback_amount'] = 0;
            return $data;
          }
          break;
          case 'source':
            $platform = $inputs['source_id'];
            // if($platform!=5)
            if(true)
          {
            $all_platforms = CheckCoupon::where('coupon_id',$check->coupon_id)->where('check','source')->get();
            $all_platforms = $all_platforms->pluck('value')->toArray();
            if(!in_array($platform,$all_platforms))
            {
              $data['success'] = 0;
              $data['success'] = 0;
               if(in_array(1,$all_platforms) && in_array(4,$all_platforms))
            $data['message'] = 'Coupon is valid only on our Apps';
            else if(in_array(1,$all_platforms))
            $data['message'] = 'Coupon is valid only on our Android App Only';
            else if(in_array(4,$all_platforms))
            $data['message'] = 'Coupon is valid only on our IOS App Only';
              else
              $data['message'] = 'This Coupon is platform specific';
              $data['discount_applicable_on'] = 0;
              $data['max_discount_amount'] = 0;
              $data['max_cashback_amount'] = 0;
              return $data;
            }
          }
            break;
          case 'on_payment_gateway':
            $payment_gateways = CheckCoupon::where('coupon_id',$check->coupon_id)->where('check','on_payment_gateway')->get();
            $pg_ids = $payment_gateways->pluck('value')->toArray();
            if(!in_array($pg_id, $pg_ids))
            {
              {
                $data['success'] = 0;
                $data['message'] = 'This Coupon is applicable on some Payment Gateways Only.';
                $data['discount_applicable_on'] = 0;
                $data['max_discount_amount'] = 0;
                $data['max_cashback_amount'] = 0;
                return $data;
              }
            }
            break;
          case 'on_kitchen':
            if($kitchen_check == 0 ) {
              $kitchen_checks = CheckCoupon::where('coupon_id', $check->coupon_id)->where('check', 'on_kitchen')->get();
              $kitchen_ids = $kitchen_checks->pluck('value')->toArray();
              $area_ids = [];
              foreach ($kitchen_ids as $kid) {
                $kitchen = Kitchen::find($kid);
                $area_ids += $kitchen->areas->pluck('id')->toArray();
              }
              $logger->log($area_id.'***--'.json_encode($area_id));
              if (!in_array($area_id, $area_ids)) {
                {
                  $data['success'] = 0;
                  $data['message'] = 'This Coupon is applicable on some Kitchens Only.';
                  $data['discount_applicable_on'] = 0;
                  $data['max_discount_amount'] = 0;
                  $data['max_cashback_amount'] = 0;
                  return $data;
                }
              }
              $kitchen_check = 1;
            }
            break;
          case 'for_user':
            $users = CheckCoupon::where('coupon_id',$check->coupon_id)->where('check','for_user')->get();
            $user_ids = $users->pluck('value')->toArray();
            if(!in_array($user_id, $user_ids))
            {
              $data['success'] = 0;
              $data['message'] = 'This Coupon is applicable on some users Only.';
              $data['discount_applicable_on'] = 0;
              $data['max_discount_amount'] = 0;
              $data['max_cashback_amount'] = 0;
              return $data;
            }
            break;
          case 'by_user':
            $admins = CheckCoupon::where('coupon_id',$check->coupon_id)->where('check','by_user')->get();
            $admin_ids = $admins->pluck('value')->toArray();
            if(!in_array($login_user->id, $admin_ids))
            {
              {
                $data['success'] = 0;
                $data['message'] = 'This Coupon can be applied by some admins Only.';
                $data['discount_applicable_on'] = 0;
                $data['max_discount_amount'] = 0;
                $data['max_cashback_amount'] = 0;
                return $data;
              }
            }
            break;
          case 'on_area':
              $areas = CheckCoupon::where('coupon_id',$check->coupon_id)->where('check','on_area')->get();
              $area_ids = $areas->pluck('value')->toArray();
            $logger->log($area_id.'***--'.json_encode($area_ids));
            if(!in_array($area_id, $area_ids))
              {
                {
                  $data['success'] = 0;
                  $data['message'] = 'This Coupon is applicable on some areas Only.';
                  $data['discount_applicable_on'] = 0;
                  $data['max_discount_amount'] = 0;
                  $data['max_cashback_amount'] = 0;
                  return $data;
                }
              }
            break;
          case 'on_state':
              $states = CheckCoupon::where('coupon_id',$check->coupon_id)->where('check','on_state')->get();
              $state_ids = $states->pluck('value')->toArray();
              if(!in_array($area->city->state->id, $state_ids))
              {
                {
                  $data['success'] = 0;
                  $data['message'] = 'This Coupon is applicable on some states Only.';
                  $data['discount_applicable_on'] = 0;
                  $data['max_discount_amount'] = 0;
                  $data['max_cashback_amount'] = 0;
                  return $data;
                }
              }
            break;
          case 'on_city':
              $cities = CheckCoupon::where('coupon_id',$check->coupon_id)->where('check','on_city')->get();
              $city_ids = $cities->pluck('value')->toArray();
              if(!in_array($area->city->id, $city_ids))
              {
                {
                  $data['success'] = 0;
                  $data['message'] = 'This Coupon is applicable on some cities Only.';
                  $data['discount_applicable_on'] = 0;
                  $data['max_discount_amount'] = 0;
                  $data['max_cashback_amount'] = 0;
                  return $data;
                }
              }
            break;
          case 'cuisine':
              $cuisine = Cuisine::find($check->value);
              $dependency = CheckCoupon::where('check','quantity')->where('group',$check->group)->first();
              $quantity = $dependency->value;
              $meals = $items_checks;
              foreach ($meals as $meal) {
                $check_meal = Item::find($meal['id']);
                if($cuisine->id==$check_meal->itemable->cuisine->id&&$check_meal->itemable->status==1)
                {
                  $meal_quantity = $quantity - $meal['quantity'];
                  if($meal_quantity<=0) {
                    $discount_applicable_on += $check_meal->itemable->discount_price * $quantity;
                    $quantity-=$meal['quantity'];
                    break;
                  }else {
                    $quantity-=$meal['quantity'];
                    $discount_applicable_on += $check_meal->itemable->discount_price * $quantity;
                  }
                }
                }

              if($quantity>0)
               {
                 {
                   $data['success'] = 0;
                   $data['message'] = 'This Coupon is applicable on '.$cuisine->name.'.Add '.$quantity.'More';
                   $data['discount_applicable_on'] = 0;
                   $data['max_discount_amount'] = 0;
                   $data['max_cashback_amount'] = 0;
                   return $data;
                 }
               }
          break;
          case 'category' :
              $category = Category::find($check->value);
            $dependency = CheckCoupon::where('check','quantity')->where('group',$check->group)->first();
              $quantity = $dependency->value;
              $meals = $items_checks;
              foreach ($meals as $meal) {
                $check_meal = Item::find($meal['id']);
                if($category->id==$check_meal->itemable->category->id&&$check_meal->itemable->status==1)
                {
                  $meal_quantity = $quantity - $meal['quantity'];
                  if($meal_quantity<=0) {
                    $discount_applicable_on += $check_meal->itemable->discount_price * $quantity;
                    $quantity-=$meal['quantity'];
                    break;
                  }else {
                    $quantity-=$meal['quantity'];
                    $discount_applicable_on += $check_meal->itemable->discount_price * $quantity;
                  }
                }
              }
              if($quantity>0)
               {
                 $data['success'] = 0;
                 $data['message'] = 'This Coupon is applicable on '.$category->name;
                 $data['discount_applicable_on'] = 0;
                 $data['max_discount_amount'] = 0;
                 $data['max_cashback_amount'] = 0;
                 return $data;
               }
            break;
          case 'meal':
            $check_meal = Meal::find($check->value);
            $dependency = CheckCoupon::where('check','quantity')->where('group',$check->group)->first();
            $quantity = $dependency->value;
            if($meals)
            {
              foreach($meals as $meal)
              {
                $test_meal = Meal::find($meal['id']);
                $meal_quantity = $meal['quantity'];
                if($test_meal->id == $check->value&&$test_meal->status==1)
                {
                  $quantity-=$meal_quantity;
                }
              }
            }
            if($quantity>0)
            {
              $data['success'] = 0;
              $data['message'] = 'This Coupon is applicable on '.$check_meal->name.' - '.$quantity;
              $data['discount_applicable_on'] = 0;
              $data['max_discount_amount'] = 0;
              $data['max_cashback_amount'] = 0;
              return $data;
            }
          break;
          case 'combo':
            $check_combo = Combo::find($check->value);
            $dependency = CheckCoupon::where('check','quantity')->where('group',$check->group)->first();
            $quantity = $dependency->value;
            if($combos)
            {
              foreach($combos as $combo)
              {
                $test_combo = Combo::find($combo['id']);
                $combo_quantity = $combo['quantity'];
                if($test_combo->id == $check->value&&$test_meal->status==1)
                {
                  $quantity-=$combo_quantity;
                }
              }
            }
            if($quantity>0)
            {
              $data['success'] = 0;
              $data['message'] = 'This Coupon is applicable on '.$check_combo->name.' - '.$quantity;
              $data['discount_applicable_on'] = 0;
              $data['max_discount_amount'] = 0;
              $data['max_cashback_amount'] = 0;
              return $data;
            }
            break;
          case 'item':
            $item = Item::find($check->value);
            $dependency = CheckCoupon::where('check','quantity')->where('group',$check->group)->first();
            $quantity = $dependency->value;
            $items = $items_checks;
            if($items!=null)
              foreach ($items as $cart_item) {
                $check_item = Item::find($cart_item['id']);
                if($item->id==$check_item->id&&$check_item->itemable->status==1)
                {
                  $item_quantity = $quantity - $cart_item['quantity'];
                  if($item_quantity<=0) {
                    $discount_applicable_on += $check_item->itemable->discount_price * $quantity;
                    $quantity-=$cart_item['quantity'];
                    break;
                  }else {
                    $quantity-=$cart_item['quantity'];
                    $discount_applicable_on += $check_item->itemable->discount_price * $quantity;
                  }
                }
              }
            if($quantity>0)
            {
              $data['success'] = 0;
              $data['message'] = 'This Coupon is applicable on '.$item->itemable->name.'. Add '.$quantity.'More';
              $data['discount_applicable_on'] = 0;
              $data['max_discount_amount'] = 0;
              $data['max_cashback_amount'] = 0;
              return $data;
            }
            break;

          default:

            break;
        }
    }

    if($discount_applicable_on==0)
    {
      $discount_applicable_on = $amount;
    }

    $data['success'] = 1;
    $data['message'] = 'Coupon Applied Successfully';
    $data['discount_applicable_on'] = $discount_applicable_on;
    $data['max_discount_amount'] = $max_discount_amount==""?-1:$max_discount_amount;
    $data['max_cashback_amount'] = $max_cashback_amount==""?-1:$max_cashback_amount;
    return $data;
  }

  public function calculateAmount($request)
  {
    $meals = $request->get('meals');

    $combos = $request->get('combos');

    $amount = 0;

    if($meals!=null)

    {

      foreach ($meals as $meal) {

      if($meal['quantity']!='0')

      {

        $item = Item::where('itemable_id',$meal['id'])->where('itemable_type','App\Models\Meal')->first();

        if($item->itemable->discount_price !=0)

        $amount += $item->itemable->discount_price * $meal['quantity'];

      else

        $amount += $item->itemable->original_price * $meal['quantity'];

      }

    }
  }

    if($combos!=null)

    {

    foreach ($combos as $combo) {

      if($combo['quantity']!='0')

      {

        $item = Item::where('itemable_id',$combo['id'])->where('itemable_type','App\Models\Combo')->first();

        if($item->itemable->discount_price !=0)

          $amount += $item->itemable->discount_price * $combo['quantity'];

        else

          $amount += $item->itemable->original_price * $combo['quantity'];
      }

    }

    }

    return $amount;

  }

  // Credit Loyality Points

  public function creditLoyality($order)
  {
    $loyalityLogger = new LoyalityLogger;
    $user = User::find($order->user_id);

    //find user's referral code
    $user_coupon = Coupon::where('code',$user->referral_code)->first();

    //if user's referral code is not active, make it active as he has a successful order now!
    if($user_coupon->status!=1)
    {
      $user_coupon->status = 1;
      $user_coupon->save();
    }


    $actual_cashback = in_array($order->status,[1,2,4,7]) ? ceil($order->paymentInfo->cashback_buff) : 0;
    $user->points += $actual_cashback;

    $payment = Payment::where('order_id',$order->id)->first();
    if($actual_cashback !=0) {
      $loyalityLogger->log($actual_cashback . ' Bueno Loyality Points Credited to' . $user->full_name . ' Id:' . $user->id);
      $payment-> cashback_credited = $actual_cashback; // save the credits earned into order table
    }
    $payment->cashback_buff = 0;
    $payment->save();

    if($order->coupon) {
      $coupon_code = $order->coupon->code;
      $referrer = User::where('referral_code', $coupon_code)->first();
    }
    else
    {
      $referrer = null;
    }
    if($referrer!=null)
    {
      $user->referrer_id = $referrer->id;
      $referring_points = Setting::where('key','referrer_points')->first()->value;
      $referrer->points += $referring_points;
      $referrer->save();
      $loyalityLogger->log($referring_points.' Bueno Loyality Points Credited to (Referrer) '.$referrer->full_name.' Id:'.$referrer->id);
      event(new ReferralSuccess($referrer->phone,$user->full_name,$referring_points));
    }
    $user->save();

  }

//Create a new Order - Back End Quick Order

  public function newOrder($user_id,$address_id,$request)
  {

    // User Details
    $area = Area::find($request->get('area_id'));

    $user = User::find($user_id);

    $ngo_id = $request->get('ngo_id');

    $coupon_code = $request->get('coupon_code');

    if($coupon_code!=null)
    {
      $coupon  = Coupon::where('code',$coupon_code)->first();
      $coupon_response = $this->couponValidaity($user_id,$area->id,$request,$coupon);
    }
    else
    {
      $coupon_response = ['success'=>0,'discount'=>0];
    }

    $new_order = Order::create($request->all());

    $new_order->user_id = $user_id;

    $user_address = Address::find($address_id);

    $new_order->delivery_address = $user_address->address.', '.$user_address->area->name.' ,'.$user_address->area->city->name.','.$user_address->area->city->state->name.' PIN - '.$user_address->area->pincode;

    $new_order->delivery_area_id = $user_address->area->id;

    $new_order->source_id = $request->get('source_id');

    $new_order->payment_mode_id = $request->get('payment_mode_id');

    $new_order->instruction = $request->get('instruction');

    $new_order->status = $request->get('status');

    $new_order->user_phone = $request->get('phone');

    if($request->get('company_name')!=null)
    $new_order->company_name = $request->get('company_name');

    $dateTime = new DateTime();

    $new_order->order_no = $dateTime->format('dmy').$new_order->id;

    $new_order->kitchen_id = $area->kitchen->first()->id;

    if($ngo_id!=0)
    {

      $new_order->ngo_id = $ngo_id;

      $new_order->donation_amount = $request->get('donation_amount');

    }

    $redeem_points = $request->get('redeem_points');

    if($redeem_points == 1)
    {
      $new_order->redeem_points = 1;
    }
    else{
      $new_order->redeem_points = 0;
    }

    $new_order->save();

    $meals = $request->get('meals');

    $amount = 0;

    if($meals!=null)
    {
      foreach ($meals as $meal) {

      if($meal['quantity']!='0')
      {
        $item = Item::where('itemable_id',$meal['id'])->where('itemable_type','App\Models\Meal')->first();
        ItemOrder::create(array('order_id' => $new_order->id, 'item_id' => $item->id , 'quantity' => $meal['quantity'], 'unit_price' => $item->itemable->discount_price));
        $amount += $item->itemable->discount_price * $meal['quantity'];
      }

    }
  }
    $combos = $request->get('combos');
    if($combos!=null)
    {
    foreach ($combos as $combo) {
      if($combo['quantity']!='0')
      {
        $item = Item::where('itemable_id',$combo['id'])->where('itemable_type','App\Models\Combo')->first();
        ItemOrder::create(array('order_id' => $new_order->id, 'item_id' => $item->id, 'quantity' => $combo['quantity'], 'unit_price' => $item->itemable->discount_price));
        $amount += $item->itemable->discount_price * $combo['quantity'];

      }
    }
    }

    $original_order_item_amount = $amount;
    $discount = 0;
    $payment_info['cashback_buff'] = 0;


    if($coupon_response['success']==1)
    {
      $discountable_amount = $coupon_response['discount_applicable_on'];
      $max_discount_amount = $coupon_response['max_discount_amount'];
      $max_cashback_amount = $coupon_response['max_cashback_amount'];
      $discounted_amount = 0;
      $cashback_amount = 0;
      $new_order->coupon_id = $coupon->id;
      $new_order->save();
      $gives = $coupon->gives;
      foreach ($gives as $give) {
        switch ($give->giveaway) {
          case 'discount_percent':
            $discounted_amount += ($discountable_amount*$give->value)/100;
            break;
          case 'discount_value':
            $discounted_amount += $give->value;
            break;
          case 'cashback_percent_value':
            $cashback_amount += ceil(($discountable_amount*$give->value)/100);
            break;
          case 'points_value':
            $cashback_amount += $give->value;
            break;
          case 'goody':
            $new_order_item = new ItemOrder;
            $new_order_item->order_id = $new_order->id;
            $item = Item::where('itemable_id',$give->value)->where('itemable_type','App\Models\Goody')->first();
            $new_order_item->item_id = $item->id;
            $dependency = CouponGiveAway::where('giveaway','quantity')->where('group',$give->group)->first();
            $new_order_item->quantity = $dependency->value;
            $new_order_item->unit_price = 0;
            $new_order_item->save();
            break;
          case 'meal':
            $item = Item::where('itemable_id',$give->value)->where('itemable_type','App\Models\Meal')->first();
            if($item->itemable->status==1)
            {
            $new_order_item = new ItemOrder;
            $new_order_item->order_id = $new_order->id;
            $new_order_item->item_id = $item->id;
            $dependency = CouponGiveAway::where('giveaway','quantity')->where('group',$give->group)->first();
            $new_order_item->quantity = $dependency->value;
            $new_order_item->unit_price = 0;
            $new_order_item->save();
            }
            break;
          case 'combo':
            $item = Item::where('itemable_id',$give->value)->where('itemable_type','App\Models\Combo')->first();
            if($item->itemable->status==1) {
              $new_order_item = new ItemOrder;
              $new_order_item->order_id = $new_order->id;
              $new_order_item->item_id = $item->id;
              $dependency = CouponGiveAway::where('giveaway', 'quantity')->where('group', $give->group)->first();
              $new_order_item->quantity = $dependency->value;
              $new_order_item->unit_price = 0;
              $new_order_item->save();
            }
            break;
          case 'item':
            $item = Item::find($give->value);
            $dependency = CouponGiveAway::where('giveaway', 'quantity')->where('group', $give->group)->first();
            if($item->itemable_type=='App\Models\Goody') {
              $add_item = 1;
            }
            else if($item->itemable->status == 1) {
              $stock = $item->getStockByKitchenId($new_order->kitchen_id);
              if($stock->value>=$dependency->value)
              $add_item = 1;
              else
              $add_item = 0;
              }
            else {
              $add_item = 0;
            }
              if($add_item==1) {
                $new_order_item = new ItemOrder;
                $new_order_item->order_id = $new_order->id;
                $new_order_item->item_id = $give->value;
                $new_order_item->quantity = $dependency->value;
                $new_order_item->unit_price = 0;
                $new_order_item->save();
              }
            break;
          default:

            break;
        }
      }

      if($max_cashback_amount<0)
        $payment_info['cashback_buff'] = $cashback_amount ;
      else if($max_cashback_amount<$cashback_amount)
        $payment_info['cashback_buff'] = $max_cashback_amount ;
      else
        $payment_info['cashback_buff'] = $cashback_amount;

      if($max_discount_amount<0)
        $discount = $discounted_amount ;
      else if($max_discount_amount<$discounted_amount)
        $discount = $max_discount_amount ;
      else
        $discount = $discounted_amount;


    }

    if($discount<0)
    {
      $discount = 0;
    }

    $amount = $original_order_item_amount - $discount;

    if($amount < 0 )
    {
      $amount = 0;
    }

    $used_points = 0;
    $user_points_used = 0;
    // Redeem Points
    if($redeem_points==1)
    {
      $user = User::find($new_order->user_id);
      $user_points = $user->points;
      $user_points_used = 0; //ceil($user_points/2);
      if($user_points>0)
      {
        if($user_points>=($amount/2))
        {
          $user_points_used = $amount/2;
        }
        else{
          $user_points_used = $user_points;
        }
       
        $amount -= $user_points_used;
        $user->points -= $user_points_used;
        $user->save();
      }
    }

    if($amount < 0 )
    {
      $amount = 0;
    }
    //Addtional Charges

    $service_tax = $new_order->kitchen->service_tax;

    $vat = $new_order->kitchen->vat;

    $delivery_charge = $new_order->kitchen->delivery_charge;

    $packaging_charge = $new_order->kitchen->packaging_charge;

    $service_charge = $new_order->kitchen->service_charge;

    //Tax and charges calculation

    $original_amount_copy = $amount;

    $service_tax_amount = $amount * $service_tax * 0.01;

    $vat_amount = $amount * $vat * 0.01;

    $total_tax =  $service_tax_amount + $vat_amount + $delivery_charge + $service_charge + $packaging_charge;

    $amount = $original_amount_copy + $total_tax;


    // Generate Invoice

    //Order Amount

    $this->newOrderInvoice($new_order->id,'Order Amount',$original_order_item_amount);

    //Discount

    $this->newOrderInvoice($new_order->id,'Discount',$discount);

    //Points Redeemed

    $this->newOrderInvoice($new_order->id,'Points Redeemed',$user_points_used);

    //Service Tax

    $this->newOrderInvoice($new_order->id,'Service Tax',$service_tax_amount);

    //VAT

    $this->newOrderInvoice($new_order->id,'VAT',$vat_amount);

    //Delivery Charge

    $this->newOrderInvoice($new_order->id,'Delivery Charge',$delivery_charge);

    //Packaging Charge

    $this->newOrderInvoice($new_order->id,'Packaging Charge',$packaging_charge);

   // Service Charge

    $this->newOrderInvoice($new_order->id,'Service Charge',$service_charge);

    //Total Amount

    $this->newOrderInvoice($new_order->id,'Total Amount',$amount);

    //Donation Amount

    if($new_order->donation_amount)
      $invoice_amount = $new_order->donation_amount ;
    else
      $invoice_amount = 0;

    $this->newOrderInvoice($new_order->id,'Donation Amount',$invoice_amount);

    // Payment Details
    $payment_info['order_id'] = $new_order->id;
    $payment_info['payment_mode_id'] = $request->get('payment_mode_id');
    $payment_info['status'] = $request->get('payment_status');
    $payment_info['amount'] = $amount ;
    $payment_info['settlement_amount'] = $amount ;

    $cashback_level_amount = $new_order->user->membership->loyalty_points * 0.01 * ($original_amount_copy);
    if(!in_array($new_order->source_id, array_values(config('bueno.source'))))
      $cashback_level_amount = 0;
    $payment_info['cashback_buff']+= $new_order->coupon ? 0 : $cashback_level_amount;

    Payment::create($payment_info);
    //update Stock
    $meals = $request->get('meals');
    $combos = $request->get('combos');

    if($meals!=null)
      foreach ($meals as $meal) {
        $item = Item::where('itemable_id',$meal['id'])->where('itemable_type','App\Models\Meal')->first();
        $this->updateStock($new_order->kitchen_id,$item->id,$meal['quantity']);
      }

    if($combos!=null)
      foreach ($combos as $combo) {
        $item = Item::where('itemable_id',$combo['id'])->where('itemable_type','App\Models\Combo')->first();
        $combo = $item->itemable;
        foreach ($combo->meals as $meal) {
          $item = Item::where('itemable_id',$meal->meal_id)->where('itemable_type','App\Models\Meal')->first();
          $this->updateStock($new_order->kitchen_id,$item->id,$combo['quantity']*$meal->quantity);
        }
      }
    return $new_order;
  }

  //Restock Items

  public function reStockCancelledOrderItems($order)
  {
    $items = $order->items;
    foreach($items as $item )
    {
      if($item->itemable_type != 'App\Models\Goody')
      {
        $stock = Stock::where('kitchen_id',$order->kitchen_id)->where('item_id',$item->id)->get()->first();
        $stock->value+= $item->pivot->quantity;
        $stock->save();
      }
    }
    return $items;
  }

  public function getTotalSales($kitchen_id = "all")
  {
    $payments = new Payment;

    $payments = $payments->where('status',3);
    if($kitchen_id!="all") {
      $payments = $payments->where('kitchen_id',$kitchen_id);
    }
    $payments = $payments->get();

    return $this->addSalesAmount($payments);
  }

  public function getTotalSalesByDates($start,$end,$kitchen_id="all")
  {
    $payments = new Payment;
    $payments = $payments->where('updated_at','>=',$start)->where('updated_at','<=',$end);
    $payments = $payments->where('status',3);
    if($kitchen_id!="all") {
      $payments = $payments->where('kitchen_id',$kitchen_id);
    }
    $payments = $payments->get();
    return $this->addSalesAmount($payments);
  }

  public function getTotalOrdersByDates($start,$end)
  {
    return Order::where('created_at','>=',$start)->where('created_at','<=',$end)->get();
  }

  public function getTotalSalesByDays($days=0)
  {
    $period = '-'.$days.' days';
    $today = new DateTime('00:00:00');
    $payments = Payment::where('created_at','>=',$today->modify($period))->where('status',3)->get();
    return $this->addSalesAmount($payments);
  }

  public function getTotalSalesByMonths($months)
  {
    $period = '-'.$months.' months';
    $today = new DateTime();
    $payments = Payment::where('updated_at','>',$today->modify('last day of previous month'))->get();
    return $this->addSalesAmount($payments);
  }



  public function getAllStatuses()
  {
    return OrderStatus::get();
  }

  public function getLastDaysOrder($days,$kitchen_id="all")
  {
    $orders = $this->getAllOrdersByDays($days,$kitchen_id)->groupBy(function($date) {
        return \Carbon\Carbon::parse($date->created_at)->format('d-m-y');
    });
    $today = new DateTime();
    $period = '-'.$days.' days';
    $days_back_30 = new DateTime();
    $days_back_30->modify($period);
    $orders = $orders->toArray();
    $counter = 0;
    while($days_back_30<=$today)
    {
      if (array_key_exists($days_back_30->format('d-m-y'), $orders)) {
          $data[$counter] = count($orders[$days_back_30->format('d-m-y')]);
      }
      else
        $data[$counter] = 0;

      $counter++;
      $days_back_30->modify('1 days');
    }
    return $data;

  }

  public function getLastDaysSales($days,$kitchen_id="all")
  {
    $orders = $this->getAllOrdersByDays($days,$kitchen_id)->groupBy(function($date) {
        return \Carbon\Carbon::parse($date->created_at)->format('d-m-y');
    });
    $today = new DateTime();
    $period = '-'.$days.' days';
    $days_back = new DateTime();
    $days_back->modify($period);
    $counter = 0;
    while($days_back<=$today)
    {
      $sum=0.00;
      if(isset($orders[$days_back->format('d-m-y')])){
              $orderCollection  = $orders[$days_back->format('d-m-y')];
              foreach ($orderCollection as $order ) {
                if($order->paymentInfo && $order->paymentInfo->status==3)
                  $sum+=$order->paymentInfo->amount;
          }
        }
      $data[$counter] = $sum;
      $counter++;
      $days_back->modify('1 days');

    }
    return $data;

  }

  public function getLastDaysSalesByDates($start,$end,$kitchen_id="all")
  {
    $orders = $this->getAllOrdersByDates($start,$end,$kitchen_id)->groupBy(function($date) {
        return \Carbon\Carbon::parse($date->updated_at)->format('d-m-y');
    });

    $counter = 0;

    while($start<=$end)
    {
      $sum=0.00;
      if(isset($orders[$start->format('d-m-y')])){
              $orderCollection  = $orders[$start->format('d-m-y')];
              foreach ($orderCollection as $order ) {

                  $sum+=$order->paymentInfo->amount;

          }
        }
      $data[$counter] = $sum;
      $counter++;
      $start->modify('1 days');

    }
    return $data;

  }

  public function getSalesByKitchenId($days,$id)
  {
    $orders = $this->getAllOrdersByDays($days)->groupBy(function($date) {
        return \Carbon\Carbon::parse($date->created_at)->format('d-m-y');
    });

    $today = new DateTime();
    $period = '-'.$days.' days';
    $days_back = new DateTime();
    $days_back->modify($period);
    $kitchen = Kitchen::find($id);
    $kitchen_areas = $kitchen->areas->toArray();
      $counter = 0;

    $data = null;

    while($days_back<=$today)
    {
      $sum=0;
      if(isset($orders[$days_back->format('d-m-y')])){
              $orderCollection  = $orders[$days_back->format('d-m-y')];
              foreach ($orderCollection as $order ) {
              foreach ($kitchen_areas as $kitchen_area) {
                if($kitchen_area['id']==$order->delivery_area_id&& $order->paymentInfo && $order->paymentInfo->status==3)
                  {$sum+=$order->paymentInfo->amount;}
            }
          }
        }
      $data[$counter] = $sum;
      $counter++;
      $days_back->modify('1 days');

    }
    return $data;

  }

  public function getOrdersByKitchenId($id)
  {
    $orders = Order::get();
    $kitchen = Kitchen::find($id);
    $areas = $kitchen->areas;
    $areaIds = $areas->pluck('id')->toArray();

    $counter = 0;
    foreach ($orders as $order) {
      if(in_array($order->address->area_id, $areaIds))
        $counter++;
    }
    return $counter;

  }

  public function getPaymentByOrderId($id)
  {
    $order = $this->getOrderById($id);

    return Payment::where('order_id',$order->id)->first();
  }

  public function getOrdersByCoupounId($id)
  {
    return Order::whereCouponId($id)->get();
  }

  public function getOrdersWhereCouponApplied()
  {
    return Order::where('coupon_id','!=',null )->orWhere('coupon_id','!=',0)->get();
  }

  public function exportOrders()
  {
    $csv = [
            'Order No',
            'Name',
            'Email',
            'Mobile No',
            'Address',
            'Locality',
            'Source',
            'Coupon Code',
            'Order Amount',
            'Discount',
            'Total Tax',
            'Total Amount',
            'Order Recieved Date',
            'Order Recieved Time',
            'Order Packed',
            'Order Dispatched',
            'Order Delivered',
            'Order Status',
            'Payment Status',
            'Payment Mode',
            'No of Dishes',
            'Kitchen Name'];
        $fp = fopen(storage_path().'/dumps/orders-export.csv', 'w');
        fputcsv($fp,$csv);
        Order::chunk(500, function($orders) use($fp)
        {
              foreach ($orders as $order) {
               
                $discount = $order->invoice->where('charge_for','Discount')->first();
                $discount = $discount ? $discount->amount : 0;
                $vat = $order->invoice->where('charge_for','VAT')->first()->amount;
                $service_tax = $order->invoice->where('charge_for','Service Tax')->first()->amount;
                $service_charge = $order->invoice->where('charge_for','Service Charge')->first()->amount;
                $delivery_charge = $order->invoice->where('charge_for','Delivery Charge')->first()->amount;
                $packaging_charge = $order->invoice->where('charge_for','Packaging Charge')->first()->amount;
                $order_amount = $order->invoice->where('charge_for','Order Amount')->first()->amount;
                $pack_time = $order->logs()->where('status_to_id',7)->first();
                $dispatch_time = $order->logs()->where('status_to_id',3)->first();
                $deliver_time = $order->logs()->where('status_to_id',4)->first();
                $total_tax = $vat + $service_charge + $service_tax + $delivery_charge + $packaging_charge;
        $dishes = $order->items->count();
          
             fputcsv($fp, [
                    $order->order_no,
                    $order->user->full_name,
                    $order->user->email,
                    $order->user_phone,
                    $order->delivery_address,
                    $order->area->name,
                    $order->source->name,
                    ($order->coupon) ? $order->coupon->code : ' ',
                    $order_amount,
                    $discount,
                    $total_tax,
                    $order->paymentInfo ? $order->paymentInfo->amount : 'NA',
                    $order->created_at->format('d/m/Y'),
                    $order->created_at->format('H:i:s'),
                    $pack_time ? $pack_time->created_at->format('d/m/Y H:i:s') : null,
                    $dispatch_time ? $dispatch_time->created_at->format('d/m/Y H:i:s') : null,
                    $deliver_time ? $deliver_time->created_at->format('d/m/Y H:i:s') : null,
                    $order->statusText ? $order->statusText->name : 'NA',
                    $order->paymentInfo ? $order->paymentInfo->paymentStatus->name : 'NA',
                    in_array($order->payment_mode_id,[2]) ? 'COD' : ($order->paymentMode ? $order->paymentMode->name : 'NA'),
                    $order->items->count(),
                    $order->kitchen->name
            ]);
              }
             

           });
      

        fclose($fp);
  }

  public function transformForExcel($orders)
    {
      $transformData = [];


      $index = 0;
      $order_amount = 0;
      $order_settlement_amount = 0;

      foreach ($orders as $order)
      {

        $discount = $order->invoice->where('charge_for','Discount')->first()->amount;
        $vat = $order->invoice->where('charge_for','VAT')->first()->amount;
        $service_tax = $order->invoice->where('charge_for','Service Tax')->first()->amount;
        $service_charge = $order->invoice->where('charge_for','Service Charge')->first()->amount;
        $delivery_charge = $order->invoice->where('charge_for','Delivery Charge')->first()->amount;
        $packaging_charge = $order->invoice->where('charge_for','Packaging Charge')->first()->amount;
        $order_amount = $order->invoice->where('charge_for','Order Amount')->first()->amount;
        $pack_time = $order->logs()->where('status_to_id',7)->first();
        $dispatch_time = $order->logs()->where('status_to_id',3)->first();
        $deliver_time = $order->logs()->where('status_to_id',4)->first();
        $total_tax = $vat + $service_charge + $service_tax + $delivery_charge + $packaging_charge;
        $dishes = $order->items->count();
        $total_dishes = 0;
        $total_tax_all = 0;
        $total_discount = 0;
        $total_order_amount = 0;
        $total_revenue = 0;


        $transformData[$index] = [
            'Order No'              => $order->order_no,
            'Name'                  => $order->user->full_name,
            'Email'                 => $order->user->email,
            'Mobile No'             => $order->user_phone,
            'Address'               => $order->delivery_address,
            'Locality'              => $order->area->name,
            'Source'              => $order->source->name,
            'Coupon Code'           => ($order->coupon) ? $order->coupon->code : ' ',
            'Order Amount'               => $order_amount,
            'Discount'              => $discount,
            'Total Tax'             => $total_tax,
            'Total Amount'           => $order->paymentInfo ? $order->paymentInfo->amount : 'NA',
            'Order Recieved Date' => $order->created_at->format('d/m/Y H:i:s'),
            'Order Recieved Time' => $order->created_at->format('H:i:s'),
            'Order Packed' => $pack_time ? $pack_time->created_at->format('d/m/Y H:i:s') : null,
            'Order Dispatched' => $dispatch_time ? $dispatch_time->created_at->format('d/m/Y H:i:s') : null,
            'Order Delivered' => $deliver_time ? $deliver_time->created_at->format('d/m/Y H:i:s') : null,
            'Order Status' => $order->statusText ? $order->statusText->name : 'NA',
            'Payment Status' => $order->paymentInfo ? $order->paymentInfo->paymentStatus->name : 'NA',
            'Payment Mode' => in_array($order->payment_mode_id,[2]) ? 'COD' :( $order->paymentMode ? $order->paymentMode->name : 'NA'),
            'No of Dishes' => $order->items->count(),
            'Kitchen Name' => $order->kitchen->name
        ];

        $total_order_amount+=$order->paymentInfo ? $order->paymentInfo->amount : 0;
        $total_dishes+=$dishes;
        $total_tax_all+=$total_tax;
        $total_discount+=$discount;
        $total_revenue+=$order_amount;

        $index++;
      }
      $transformData[$index] = [
          'Order No'              => 'Total',
          'Name'                  => null,
          'Email'                 => null,
          'Mobile No'             => null,
          'Address'               => null,
          'Locality'              => null,
          'Source'              => null,
          'Coupon Code'           => null,
          'Coupon Code'           => null,
            'Order Amount'               => $total_revenue,
            'Discount'              => $total_discount,
            'Total Tax'             => $total_tax_all,
            'Total Amount'           => $total_order_amount,
            'Order Recieved' => null,
            'Order Packed' => null,
            'Order Dispatched' => null,
            'Order Delivered' => null,
            'Order Status' => null,
            'Payment Status' => null,
            'Payment Mode' => null,
            'No of Dishes' => $total_dishes,
      ];

      return $transformData;
    }

  public function  newJoolehOrder($response)
  {
    $logger = new JoolehLogger;
    $logger->log($response);
    $order = json_decode($response,true);
    if(!isset($order['response_type']))
    {
      $jooleh_log = new JoolehLog;
    $jooleh_log->response = $response;
    $jooleh_log->order_id = $order['name'];
    $jooleh_log->oid = $order['oid'];
    $jooleh_log->delivered_on = $order['delivered_at'];
    $jooleh_log->save();
    return $jooleh_log;
  }
    return null;
  }

  public function  updateJoolehOrder($response)
  {
    $response = json_decode($response,true);
    $jooleh_log = null;
    if(!isset($response['response_type'])) {
      $oid = $response['oid'];
      $jooleh_log = JoolehLog::where('oid', $oid)->first();
      if ($jooleh_log == null)
        return false;
      $order = $jooleh_log->order;
      $delivery_guy_uid = $response['delivery_guy_uid'];
      if ($delivery_guy_uid != null) {
        $delivery_boy = DeliveryBoy::where('jooleh_uid', $delivery_guy_uid)->first();
        if ($delivery_boy)
          $order->delivery_boy_id = $delivery_boy->id;
        else {
          $delivery_boy = $this->createDeliveryBoy($response['delivery_guy_name'], $response['delivery_guy_mobile'], $order->kitchen_id, $delivery_guy_uid, 'No Data', 'No Data');
          $order->delivery_boy_id = $delivery_boy->id;
        }
      }
      $jooleh_log->response = $jooleh_log->response .$order->statusText->name . ' : ' . json_encode($response);
      $tracking_url = $response['tracking_url'];

      if ($tracking_url) {
        $order->tracking_url = $tracking_url;
      }

      $jooleh_log->save();

      $order->save();
    }
    else
    {
      return false;
    }
    return $jooleh_log;
  }

  //Coupon Checks

  public function minOrderAttribute(CheckCoupon $check,$attribute)
  {
    if($check->value<=$attribute)
      return true;
    else
      return false;
  }

  //update Stock

  public function updateStock($kitchen_id,$item_id,$quantity)
  {
    $meal = Stock::where('kitchen_id', $kitchen_id)->where('item_id',$item_id)->get()->first();
    if($meal !=null) {
      $meal->value -= $quantity;
      $meal->save();
    }
  }

  public function updateReStock($kitchen_id,$item_id,$quantity)
  {
    $meal = Stock::where('kitchen_id',$kitchen_id)->where('item_id',$item_id)->get()->first();
    if($meal) {
      $meal->value = $meal->value + $quantity;
      $meal->save();
    }
  }


  //Update Order

  public function updateOrderById($id,$request)
  {
    $order = Order::find($id);

    $order->save();

    return $order;
  }

  public function createUserOrder($inputs)
  {
    $logger = new DebugLogger;
    $logger->log('Inputs : '.json_encode($inputs));
    $address = Address::find($inputs['address_id']);

    $area = $address->area;

    $inputs['coupon_code'] = isset($inputs['coupon_code']) ? $inputs['coupon_code'] : '';
    $coupon = Coupon::where('code', $inputs['coupon_code'])->where('status', 1)->first();

    $coupon_id = $coupon ? $coupon->id : null;
    $logger->log('Coupon Id : '.json_encode($coupon_id));

    $ngo = null;

    if(isset($inputs['ngo_id'])){
      $ngo =  Ngo::where('id', $inputs['ngo_id'])->where('status', 1)->first();
    }

    $payment_mode = PaymentMode::find($inputs['payment_mode_id']);

    $order = Order::create([
      'user_id' => auth()->user()->id,
      'delivery_address'  => $address->address . ', ' . $area->name . ', -' . $address->pincode,
      'delivery_area_id'  => $address->area_id,
      'source_id' => config('bueno.source.web'),
      'payment_mode_id' => $payment_mode ? $payment_mode->id : 2,
      'instruction' => isset($inputs['instruction']) ? $inputs['instruction'] : '',
      'status' => 1,
      'coupon_id' => $coupon_id,
      'delivery_boy_id' => 0,
      'ngo_id'  => $ngo ? $ngo->id: 0,
      'donation_amount' => $ngo ? $ngo->default_donation_amount : 0,
      'kitchen_id'  => $area->firstKitchen()->id,
      'redeem_points' => isset($inputs['redeem_points']) ? 1 : 0,
      'user_phone' => auth()->user()->phone,
    ]);


    $dateTime = new DateTime();
    $order->order_no = $dateTime->format('dmy').$order->id;
    $order->save();

    $this->createOrderItems($order, $inputs);

    $this->createOrderPayment($order,$inputs);

    if($order->instruction)
    $this->addOrderLog($order->id,['order'=>1,'payment' =>$order->paymentInfo->status],['order'=>2,'payment' =>$order->paymentInfo->status],'New Order Initiated. instruction : '.$order->instruction);
    else
      $this->addOrderLog($order->id,['order'=>1,'payment' =>$order->paymentInfo->status],['order'=>2,'payment' =>$order->paymentInfo->status],'New Order Initiated.');


    if($order->paymentInfo->amount == 0)
    {
      $order->update([
        'payment_mode_id' => 2
      ]);
    }

    return $order;
  }

  public function   createOrderApi($inputs)
  {
    $user = User::where('phone', $inputs['mobile'])->first();

    $source =  OrderSource::where('name', $inputs['tag'])->first();

    if(!$source) throw new CheckoutValidationException('Invalid Source');

    if(!$user)
    {
      $user = User::create([
          'phone'       => $inputs['mobile'] ,
          'first_name'  => isset(explode(' ', $inputs['name'])[0]) ? explode(' ', $inputs['name'])[0] : '',
          'last_name'   => isset(explode(' ', $inputs['name'])[1]) ? explode(' ', $inputs['name'])[1] : '',
          'email'       => $inputs['email']
      ]);

      event(new UserWasCreated($user));
    }

    $inputs['redeem_points'] = isset($inputs['redeem_points']) ? $inputs['redeem_points'] : 0;

    $area = Area::where('name', $inputs['locality'])->first();

    if(!$area) throw new CheckoutValidationException('Invalid Area Selected');

    $inputs['order_amount'] = isset($inputs['order_amount']) ? $inputs['order_amount'] : 0;
    $inputs['vat'] = isset($inputs['vat']) ? $inputs['vat'] : 0;

    $order_amount_base = $inputs['order_amount']-$inputs['vat']+$inputs['redeem_points'];

    if($inputs['redeem_points'] > ceil($order_amount_base/2)) throw new CheckoutValidationException('You can only redeeem 50% of order amount in one order.');

    if(($user->points - $inputs['redeem_points']) < 0) throw new CheckoutValidationException('Invalid Bueno Points');
    
    if($area)
    {
      $area_api_id = $area->id;
    }

    //Address
    $address = $user->addresses->where('address',$inputs['address']);

    if($address->count()==0)
    {
      Address::create([
        'address' => $inputs['address'],
        'user_id' => $user->id,
        'area_id' => $area ? $area->id : 1
        ]);
    }

    $coupon = isset($inputs['coupon_code']) ? Coupon::where('code', $inputs['coupon_code'])->first() : null;

    if(isset($inputs['kitchen_id']))
    {
      $kitchen_api_id = $inputs['kitchen_id'];
      switch ($kitchen_api_id) {
       case 2:
          $area_api_id = 47;
          $address_api = 'Sector 30 Bueno';
          break;
       case 1:
          $area_api_id = 5;
          $address_api = 'DLF Phase 3 Bueno';
        break;
        
      }
    }

    
    $order = Order::create([
      'user_id' => $user->id,
      'user_phone'  => $inputs['mobile'],
      'delivery_address'  => $inputs['address'] ? $inputs['address'] : $address_api,
      'delivery_area_id'  => $area ? $area->id : $area_api_id,
      'source_id' => $source->id,
      'payment_mode_id' => $inputs['paymode'],
      'resource_order_no' => $inputs['order_no'],
      'instruction' => $inputs['instruction'],
      'status' => 2,
      'coupon_id' => $coupon ? $coupon->id : null,
      'kitchen_id'  => $area ? $area->firstKitchen()->id : (isset($inputs['kitchen_id']) ? $inputs['kitchen_id'] : 1),
      'redeem_points' => $inputs['redeem_points'],
      'longitude'=> isset($inputs['longitude']) ? $inputs['longitude']:null,
      'latitude'=> isset($inputs['latitude']) ? $inputs['latitude']:null,
      'created_at' => isset($inputs['order_date']) && ( $inputs['tag'] == 'foodpanda' || $inputs['tag'] == 'zomato' || $inputs['tag'] == 'tinyowl') ? $inputs['order_date'] : Carbon::now()
  ]);

    $user->points = ($user->points - $inputs['redeem_points']) < 0 ? 0 : ($user->points - $inputs['redeem_points']);

    $user->save();

    $dateTime = new DateTime();
    $order->order_no = $dateTime->format('dmy').$order->id;
    $order->save();

    $this->createOrderItemsApi($order, $inputs);

    $this->createOrderPaymentApi($order, $inputs);

    $this->createOrderInvoiceApi($order, $inputs);


    $this->addOrderLog($order->id,['order'=>'1','payment'=>$order->paymentInfo->status],['order'=>'2','payment'=>$order->paymentInfo->status],'Order was Created from '.$order->source->name);

    return $order;
    
  }

  public function createOrderInvoiceApi($order, $inputs)
  {
    $sum = 0;

    if($order->orderItems)
    foreach($order->orderItems as $item)
    {
      $this->newOrderInvoice($order->id, $item->itemable->name , $item->itemable->discount_price, $item->pivot->quantity);

      $sum += $item->itemable->discount_price * $item->pivot->quantity;
    }

    $this->newOrderInvoice($order->id, 'Order Amount', $sum);

    $this->newOrderInvoice($order->id, 'Discount', isset($inputs['coupon_amount']) ? $inputs['coupon_amount'] : 0);

    $this->newOrderInvoice($order->id, 'VAT', $inputs['vat']);

    $this->newOrderInvoice($order->id, 'Service Charge', 0);

    $this->newOrderInvoice($order->id, 'Service Tax', 0);

    $this->newOrderInvoice($order->id, 'Donation Amount', 0);

    $this->newOrderInvoice($order->id, 'Packaging Charge', 0);

    $this->newOrderInvoice($order->id, 'Delivery Charge', isset($inputs['delivery_fee']) ? $inputs['delivery_fee'] : 0);

    $this->newOrderInvoice($order->id, 'Total Amount', $inputs['order_amount']);

    $this->newOrderInvoice($order->id, 'Points Redeemed', isset($inputs['redeem_points']) ? $inputs['redeem_points'] : 0 );
  }

  public function createOrderItemsApi($order, $inputs)
  {
    foreach($inputs['orders'] as $key => $item)
    {
      ItemOrder::create([
          'order_id'  => $order->id,
          'item_id' => $item['meal_id'],
          'quantity'  => $item['qty'],
          'unit_price'  => $item['discount_price']
      ]);
    }
  }

  public function createOrderPaymentApi($order, $inputs)
  {
    $cashback_level_amount = $order->user->membership->loyalty_points * 0.01 * ($inputs['order_amount'] - $inputs['vat']);
    if(!in_array($order->source_id, array_values(config('bueno.source'))))
      $cashback_level_amount = 0;
    Payment::create([
        'order_id'  => $order->id,
        'status'  => 2,
        'amount'  => $inputs['order_amount'],
        'payment_mode_id' => $inputs['paymode'],
        'settlement_amount'  => $inputs['order_amount'],
        'cashback_buff' =>  ($order->coupon ? 0 : $cashback_level_amount )+ (isset($inputs['rewards']) ? $inputs['rewards'] : 0),
        'transaction_details' => json_encode($inputs)
    ]);
  }

  public function updateOrderJooleh($response)
  {
    $oid = $response['oid'];
    $jooleh_log = JoolehLog::where('oid',$oid)->first();
    if($jooleh_log==null)
      return false;
    $order = $jooleh_log->order;
    $delivery_guy_uid = $response['delivery_guy_uid'];
    $status = $response['status'];
    $delivered_at = $response['delivered_at'];
    $delivered_at = trim(stripslashes($delivered_at));
    if($delivered_at!=null&&$status!=null)
    {
      $delivered_at = DateTime::createFromFormat('j/m/Y h:i:sA',$delivered_at);
      $jooleh_log->delivered_on = $delivered_at;
      if($status=='Delivered')
      {
        $orderLog = new LogOrder;
        $orderLog->user_id = config('bueno.jooleh.user_id');
        $orderLog->order_id = $order->id;
        $orderLog->comment = 'Order was Marked Delivered';
        $orderLog->status_from_id = $order->status;
        $orderLog->status_to_id = 4;
        $orderLog->created_at = $delivered_at;
        $order->status = 4;

        $picked_at = $response['picked_at'];
        if ($picked_at != null){
          $picked_at = trim(stripslashes($picked_at));
          $picked_at = DateTime::createFromFormat('j/m/Y h:i:sA', $picked_at);
        }
        $dispatched_at = $response['dispatched_at'];
        if ($dispatched_at != null){
          $dispatched_at = trim(stripslashes($dispatched_at));
          $dispatched_at = DateTime::createFromFormat('j/m/Y h:i:sA', $dispatched_at);
        }
        $order->picked_at = ($picked_at!=null) ? $picked_at : $dispatched_at;
        $order->delivered_at = $delivered_at;
        $order->total_delivery_time = round(abs($delivered_at->getTimestamp() - $order->created_at->getTimestamp()) / 60,2);
        if ($picked_at != null)
          $order->rider_delivery_time = round(abs($delivered_at->getTimestamp() - $picked_at->getTimestamp()) / 60,2);
        elseif ($dispatched_at != null)
          $order->rider_delivery_time = round(abs($delivered_at->getTimestamp() - $dispatched_at->getTimestamp()) / 60,2);

        $distance_before_pickup = $response['pickup_travel_distance'];
        $distance_total = $response['travel_distance'];
        if($distance_total){
          $order->travel_distance = $distance_total;
          if ($distance_before_pickup && $distance_before_pickup > 0 && $distance_before_pickup < $distance_total)
            $order->travel_distance -= $distance_before_pickup;
        }

        if(!$order->latitude || $order->latitude==''){
          $order->latitude = $response['customer_latitude'];
          $order->longitude = $response['customer_longitude'];
        }

        $order->delivery_latitude = $response['delivery_latitude'];
        $order->delivery_longitude = $response['delivery_longitude'];

        $order->pickup_latitude = $response['pickup_latitude'];
        $order->pickup_longitude = $response['pickup_longitude'];

        $order->computed_travel_distance = $response['computed_travel_distance_after_pickup'];
        $order->computed_rider_delivery_time = $response['computed_eta_after_pickup'];

        $payment = Payment::where('order_id',$order->id)->first();
        $payment->status = 3;
        $payment->save();
        $orderLog->save();
      }
    }
    if($delivery_guy_uid!=null)
    {
      $delivery_boy = DeliveryBoy::where('jooleh_uid',$delivery_guy_uid)->first();
      if($delivery_boy)
      $order->delivery_boy_id = $delivery_boy->id;
      else
      {
        $delivery_boy = $this->createDeliveryBoy($response['delivery_guy_name'],$response['delivery_guy_mobile'],$order->kitchen_id,$delivery_guy_uid,'No Data','No Data');
        $order->delivery_boy_id = $delivery_boy->id;
      }
    }
    $jooleh_log->response = 'Delivered : ' . json_encode($response);

    $tracking_url = isset($response['tracking_url']) ? $response['tracking_url'] : null ;

    if($tracking_url)
    {
      $order->tracking_url = $tracking_url;
    }

    $jooleh_log->save();

    $order->save();

    return Order::find($order->id);

  }

  public function createDeliveryBoy($full_name,$phone,$kitchen_id,$jooleh_uid,$vehicle_name,$vehicle_number)
  {
      $delivery_boy = new DeliveryBoy;
      $delivery_boy->full_name = $full_name;
      $delivery_boy->phone = $phone;
      $delivery_boy->jooleh_pass = $phone;
      $delivery_boy->kitchen_id = $kitchen_id;
      $delivery_boy->vehicle_number = $vehicle_number;
      $delivery_boy->vehicle_name = $vehicle_name;
      $delivery_boy->jooleh_uid = $jooleh_uid;
      $delivery_boy->save();
      return $delivery_boy;
  }

  public function createOrderItems($order, $inputs)
  {
    foreach($inputs['items'] as $key => $item)
    {
      $item_model = Item::find($item['id']);

      if($item_model){
          ItemOrder::create([
          'order_id'  => $order->id,
          'item_id' => $item_model->id,
          'quantity'  => $item['quantity'],
          'unit_price'  => $item_model->itemable->discount_price
        ]);
      }
      
    }
  }

  public function checkIfUserHasMinimumAmountOrder($user, $area_id)
  {
    $area = Area::find($area_id);

    if($area->min_order_amount > $this->calculateCartItemsTotalPrice($user->cartItems))
    {
      throw new CheckoutValidationException('You have to order minimum of Rs.' . $area->min_order_amount);
    }

  }

  public function calculateCartItemsTotalPrice($cart_items)
  {
    $total = 0;

    foreach($cart_items as $cart_item)
    {
      $total += $cart_item->item->itemable->discount_price * $cart_item->quantity;
    }

    return $total;
  }

  private function createOrderPayment($order,$inputs)
  {
    $total_amount = $this->getTotalOrderAmount($order,$inputs);
    Payment::create([
      'order_id'  => $order->id,
      'status'  => 1,
      'amount'  => $total_amount['amount'],
      'payment_mode_id' => $order->payment_mode_id,
      'settlement_amount'  => $total_amount['amount'],
      'cashback_buff' => $total_amount['cashback_buff'],
      'transaction_details' => json_encode($inputs)
    ]);
  }

  public function getTotalOrderAmount($order,$inputs)
  {
    $order_items = $order->orderItems;
    $user = $order->user;
    $items = $order_items->pluck('item_id','quantity')->toArray();

    $used_points = 0;

    $total = 0;

    foreach($order_items as $order_item)
    {
      if($order_item->itemable_type!='App\Models\Goody')
      $total += $order_item->pivot->unit_price * $order_item->pivot->quantity;
    }

    $original_order_item_amount = $total;

    $amount = $total;

    $discount = 0;

    $cashback_buff=0;

    //Coupon
    if($order->coupon)
    {
      $coupon = $order->coupon;
      $coupon_response = $this->applyCouponOnOrder($coupon,$order);
      $discount =  $coupon_response['discount'];
      $cashback_buff += $coupon_response['cashback_buff'];
      if($coupon_response['success']==0)
      {
        $order->coupon_id = null;
        $order->save();
      }
    }


    //Order Amount

    $this->newOrderInvoice($order->id,'Order Amount',$original_order_item_amount);

    if($discount>$total)
    {
      $discount = $total;
      $total = 0;
    }
    else
    {
      $total -= $discount;
    }

    //Discount Amount

    $this->newOrderInvoice($order->id,'Discount',$discount);

    //Redeem Points

    if($order->redeem_points==1)
    {
      $user_points = ceil($user->points);

      if($user_points>($total/2) && $total>0)
      {
        $used_points = ($total/2);
      }
      else if($total>0)
      {
        $used_points = $user_points;
      }
      else
      {
        $used_points = 0;
      }

      $total -= $used_points;

      if($total<0)
        $total = 0;

      $user = User::find($order->user_id);

      $user->points -= $used_points;

      $user->save();
    }


    //Points Redeemed

    $this->newOrderInvoice($order->id,'Points Redeemed',$used_points);


    // Taxes and Charges

    $service_tax = $order->kitchen->service_tax;

    $vat = $order->kitchen->vat;

    $delivery_charge = $order->kitchen->delivery_charge;

    $packaging_charge = $order->kitchen->packaging_charge;

    $service_charge = $order->kitchen->service_charge;

    $service_tax_amount = $total * $service_tax * 0.01;

    $vat_amount = $total * $vat * 0.01;


    //Delivery Charge

    $this->newOrderInvoice($order->id,'Delivery Charge',$delivery_charge);

    //Service Charge

    $this->newOrderInvoice($order->id,'Service Charge',$service_charge);

    //Service Tax

    $this->newOrderInvoice($order->id,'Service Tax',$service_tax_amount);

    //VAT

    $this->newOrderInvoice($order->id,'VAT',$vat_amount);

    //Donation Amount

    $this->newOrderInvoice($order->id,'Donation Amount',$order->donation_amount);

    //Packaging Charge

    $this->newOrderInvoice($order->id,'Packaging Charge',$packaging_charge);

    $total_before_addons = $total;

    $total += $delivery_charge+$vat_amount+$service_tax_amount+$service_charge+$packaging_charge+$order->donation_amount;

    //Total Amount

    $this->newOrderInvoice($order->id,'Total Amount',$total);

    $cashback_level_amount = $order->user->membership->loyalty_points * 0.01 * ($total_before_addons);
    if(!in_array($order->source_id, array_values(config('bueno.source'))))
      $cashback_level_amount = 0;

    $cashback_buff += $order->coupon ? 0 : $cashback_level_amount ;

    //Response

    $data['discount'] = $discount;

    $data['cashback_buff'] = $cashback_buff;

    $data['amount'] = $total;

    return $data;
  }
  public function couponOrderValidaity($coupon,$order)
  {
    $discount_applicable_on = 0;
    $checks = $coupon->checks;
    $items = $order->items;
    $pg_id = $order->payment_mode_id;
    $coupon_user = User::find($order->user_id);
    $max_discount_amount = -1;
    $max_cashback_amount = -1;
    //Coupon Active Status
    if($coupon->status==0)
    {
      $data['success'] = 0;
      $data['message'] = 'Coupon is Either Expired or Inactive';
      $data['discount_applicable_on'] = 0;
      $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
      return $data;
    }

    //Coupon Active Status
    if($coupon->status==0)
    {
      $data['success'] = 0;
      $data['message'] = 'Coupon is Either Expired or Inactive';
      $data['discount_applicable_on'] = 0;
      $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
      return $data;
    }

    $kitchen_check = 0;
    $area = Area::find($order->delivery_area_id);
    $order_count = 0;
    $amount = 0;

    foreach($items as $item)
    {
      $amount += $item->pivot->unit_price * $item->pivot->quantity;
      $order_count++;
    }

    foreach($checks as $check)
    {
      switch ($check->check) {
        case 'from':
          $current = new DateTime();
          $check_date = new DateTime($check->value);
          if ($current <= $check_date) {
            $data['success'] = 0;
            $data['message'] = 'Coupon is Valid from' . $check_date->format('d F, Y');
            $data['discount_applicable_on'] = 0;
      $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
            return $data;
          }
          break;
        case 'to':
          $current = new DateTime();
          $check_date = new DateTime($check->value);
          if ($current >= $check_date) {
            $data['success'] = 0;
            $data['message'] = 'Coupon has been Expired Since' . $check_date->format('d F Y H:i:s');
            $data['discount_applicable_on'] = 0;
      $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
            return $data;
          }
          break;
        case 'min_order_amount':
          if ($check->value > $amount) {
            $data['success'] = 0;
            $data['message'] = 'Minimum Order Amount is ' . $check->value;
            $data['discount_applicable_on'] = 0;
      $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
            return $data;
          }
          break;
        case 'total_min_quantity':
          if ($check->value > $order_count)
            if ($check->value > $amount) {
              $data['success'] = 0;
              $data['message'] = 'Minimum no of items in order must be ' . $check->value;
              $data['discount_applicable_on'] = 0;
      $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
              return $data;
            }
          break;
          case 'max_discount_amount':
            $max_discount_amount = $check->value;
            break;
          case 'max_cashback_amount':
            $max_cashback_amount = $check->value;
            break;
        case 'one_time':
          $orders = Order::where('coupon_id', $coupon->id)->get();
          $orders = $orders->filter(function ($order) {
            if($order->paymentInfo)
            return $order->paymentInfo->status == 3 || $order->status != 6 ? true : false;
            else
              false;
          });
          if ($orders->count() > 0) {
            $data['success'] = 0;
            $data['message'] = 'Coupon has been Expired.';
            $data['discount_applicable_on'] = 0;
      $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
            return $data;
          }
          break;
        case 'nth_order':
          switch ($check->value % 10) {
            case 1 :
              $number_ending = "st";
              break;
            case 2 :
              $number_ending = "nd";
              break;
            case 3 :
              $number_ending = "rd";
              break;
            default :
              $number_ending = "th";
              break;
          }
          if ($coupon_user == null) {
            if ($check->value != 1) {
              $data['success'] = 0;
              $data['message'] = 'This Coupon is applicable on ' . $check->value . $number_ending . ' Order.';
              $data['discount_applicable_on'] = 0;
      $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
              return $data;
            }
          } else if ($check->value != ($coupon_user->confirmedOrders()->count() + 1)) {
            $data['success'] = 0;
            $data['message'] = 'This Coupon is applicable on ' . $check->value . $number_ending . ' Order.';
            $data['discount_applicable_on'] = 0;
      $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
            return $data;
          }
          break;
        case 'source':
          $platform = $order->source_id;
          $all_platforms = CheckCoupon::where('coupon_id',$check->coupon_id)->where('check','source')->get();
          $all_platforms = $all_platforms->pluck('value')->toArray();
          if(!in_array($platform,$all_platforms))
          {
            $data['success'] = 0;
            if(in_array(1,$all_platforms) && in_array(4,$all_platforms))
            $data['message'] = 'Coupon is valid only on our Apps';
            else if(in_array(1,$all_platforms))
            $data['message'] = 'Coupon is valid only on our Android App Only';
            else if(in_array(4,$all_platforms))
            $data['message'] = 'Coupon is valid only on our IOS App Only';
            // else if(in_array(5,$all_platforms))
            // $data['message'] = 'Coupon is valid only on our IOS App Only';
            else
            $data['message'] = 'This Coupon is platform specific';
            $data['discount_applicable_on'] = 0;
            $data['max_discount_amount'] = 0;
            $data['max_cashback_amount'] = 0;
            return $data;
          }
          break;
        case 'on_payment_gateway':
          $payment_gateways = CheckCoupon::where('coupon_id', $check->coupon_id)->where('check', 'on_payment_gateway')->get();
          $pg_ids = $payment_gateways->pluck('value')->toArray();
          if (!in_array($pg_id, $pg_ids)) {
            {
              $data['success'] = 0;
              $data['message'] = 'This Coupon is applicable on some Payment Gateways Only.';
              $data['discount_applicable_on'] = 0;
      $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
              return $data;
            }
          }
          break;
        case 'on_kitchen':
          if ($kitchen_check == 0) {
            $kitchen_checks = CheckCoupon::where('coupon_id', $check->coupon_id)->where('check', 'on_kitchen')->get();
            $kitchen_ids = $kitchen_checks->pluck('value')->toArray();
            $area_ids = [];
            foreach ($kitchen_ids as $kid) {
              $kitchen = Kitchen::find($kid);
              $area_ids += $kitchen->areas->pluck('id')->toArray();
            }
            if (!in_array($area->id, $area_ids)) {
              {
                $data['success'] = 0;
                $data['message'] = 'This Coupon is applicable on some areas Only.';
                $data['discount_applicable_on'] = 0;
      $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
                return $data;
              }
            }
            $kitchen_check = 1;
          }
          break;
        case 'for_user':
          $users = CheckCoupon::where('coupon_id', $check->coupon_id)->where('check', 'for_user')->get();
          $user_ids = $users->pluck('value')->toArray();
          if (!in_array($order->user_id, $user_ids)) {
            $data['success'] = 0;
            $data['message'] = 'This Coupon is applicable on some users Only.';
            $data['discount_applicable_on'] = 0;
      $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
            return $data;
          }
          break;
        case 'by_user':
          $admins = CheckCoupon::where('coupon_id', $check->coupon_id)->where('check', 'by_user')->get();
          $admin_ids = $admins->pluck('value')->toArray();
          if (!in_array($order->user_id, $admin_ids)) {
            {
              $data['success'] = 0;
              $data['message'] = 'This Coupon can be applied by some admins Only.';
              $data['discount_applicable_on'] = 0;
      $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
              return $data;
            }
          }
          break;
        case 'on_area':
          $areas = CheckCoupon::where('coupon_id', $check->coupon_id)->where('check', 'on_area')->get();
          $area_ids = $areas->pluck('value')->toArray();
          if (!in_array($area->id, $area_ids)) {
            {
              $data['success'] = 0;
              $data['message'] = 'This Coupon is applicable on some areas Only.';
              $data['discount_applicable_on'] = 0;
      $data['max_discount_amount'] = 0;
      $data['max_cashback_amount'] = 0;
              return $data;
            }
          }
          break;
        case 'on_state':
          $states = CheckCoupon::where('coupon_id', $check->coupon_id)->where('check', 'on_state')->get();
          $state_ids = $states->pluck('value')->toArray();
          if (!in_array($area->city->state->id, $state_ids)) {
            {
              $data['success'] = 0;
              $data['message'] = 'This Coupon is applicable on some states Only.';
              $data['discount_applicable_on'] = 0;
              $data['max_discount_amount'] = 0;
              $data['max_cashback_amount'] = 0;
              return $data;
            }
          }
          break;
        case 'on_city':
          $cities = CheckCoupon::where('coupon_id', $check->coupon_id)->where('check', 'on_city')->get();
          $city_ids = $cities->pluck('value')->toArray();
          if (!in_array($area->city->id, $city_ids)) {
            {
              $data['success'] = 0;
              $data['message'] = 'This Coupon is applicable on some cities Only.';
              $data['discount_applicable_on'] = 0;
              $data['max_discount_amount'] = 0;
              $data['max_cashback_amount'] = 0;
              return $data;
            }
          }
          break;
        case 'cuisine':
          $cuisine = Cuisine::find($check->value);
          $dependency = CheckCoupon::where('check', 'quantity')->where('group', $check->group)->first();
          $quantity = $dependency->value;
          $meals = $items;
          foreach ($meals as $meal) {
            $check_meal = Item::find($meal->id);
              if ($cuisine->id == $check_meal->itemable->cuisine->id) {
                $meal_quantity = $quantity - $meal->pivot->quantity;
                if ($meal_quantity <= 0) {
                  $discount_applicable_on += $check_meal->itemable->discount_price * $quantity;
                  $quantity -= $meal->pivot->quantity;
                  break;
                } else {
                  $quantity -= $meal->pivot->quantity;
                  $discount_applicable_on += $check_meal->itemable->discount_price * $quantity;
                }
              }
          }

          if ($quantity > 0) {
            {
              $data['success'] = 0;
              $data['message'] = 'This Coupon is applicable on ' . $cuisine->name . '.Add ' . $quantity . 'More';
              $data['discount_applicable_on'] = 0;
              $data['max_discount_amount'] = 0;
              $data['max_cashback_amount'] = 0;
              return $data;
            }
          }
          break;
        case 'category' :
          $category = Category::find($check->value);
          $dependency = CheckCoupon::where('check', 'quantity')->where('group', $check->group)->first();
          $quantity = $dependency->value;
          $meals = $items;
          foreach ($meals as $meal) {
            $check_meal = Item::find($meal->id);
            if ($category->id == $check_meal->itemable->category->id) {
              $meal_quantity = $quantity - $meal->pivot->quantity;
              if ($meal_quantity <= 0) {
                $discount_applicable_on += $check_meal->itemable->discount_price * $quantity;
                $quantity -= $meal->pivot->quantity;
                break;
              } else {
                $quantity -= $meal->pivot->quantity;
                $discount_applicable_on += $check_meal->itemable->discount_price * $quantity;
              }
            }
          }
          if ($quantity > 0) {
            $data['success'] = 0;
            $data['message'] = 'This Coupon is applicable on ' . $category->name;
            $data['discount_applicable_on'] = 0;
            $data['max_discount_amount'] = 0;
            $data['max_cashback_amount'] = 0;
            return $data;
          }
          break;
        case 'item':
          $item = Item::find($check->value);
          $dependency = CheckCoupon::where('check', 'quantity')->where('group', $check->group)->first();
          $quantity = $dependency->value;
          if ($items != null)
            foreach ($items as $cart_item) {
              $check_item = Item::find($cart_item->id);
              if ($item->id == $check_item->id) {
                $item_quantity = $quantity - $cart_item->pivot->quantity;
                if ($item_quantity <= 0) {
                  $discount_applicable_on += $check_item->itemable->discount_price * $quantity;
                  $quantity -= $cart_item->pivot->quantity;
                  break;
                } else {
                  $quantity -= $cart_item->pivot->quantity;
                  $discount_applicable_on += $check_item->itemable->discount_price * $quantity;
                }
              }
            }
          if ($quantity > 0) {
            $data['success'] = 0;
            $data['message'] = 'This Coupon is applicable on ' . $item->itemable->name . '. Add ' . $quantity . 'More';
            $data['discount_applicable_on'] = 0;
            $data['max_discount_amount'] = 0;
            $data['max_cashback_amount'] = 0;
            return $data;
          }
          break;
      }
    }

    if($discount_applicable_on==0)
    {
      $discount_applicable_on = $amount;
    }
    $data['success'] = 1;
    $data['message'] = 'Coupon Applied Successfully';
    $data['amount'] = $amount;
    $data['discount_applicable_on'] = $discount_applicable_on;
    $data['max_discount_amount'] = $max_discount_amount==""?-1:$max_discount_amount;
    $data['max_cashback_amount'] = $max_cashback_amount==""?-1:$max_cashback_amount;
    $logger = new DebugLogger;
    $logger->log('Coupon response Data :  ' . json_encode($data));
    return $data;

  }

  public function applyCouponOnOrder($coupon,$order)
  {
    $logger = new DebugLogger;
    $validity = $this->couponOrderValidaity($coupon,$order);
    $data['success'] = 0;
    $data['message'] = 'Coupon is Either Expired or Inactive';
    $data['discount'] = 0;
    $data['cashback_buff'] = 0;

    $cashback_buff = 0;

    if($coupon->status==0 && $validity['success']!=1)
    {
      $data['success'] = 0;
      $data['message'] = 'Coupon is Either Expired or Inactive';
      $data['discount'] = 0;
      $data['cashback_buff'] = 0;
      return $data;
    }
    $discount = 0;

    if($validity['success']==1)
    {
      $amount = $validity['amount'];
      $discountable_amount = $validity['discount_applicable_on'];
      $max_discount_amount = $validity['max_discount_amount'];
      $max_cashback_amount = $validity['max_cashback_amount'];
      $discounted_amount = 0;
      $cashback_amount = 0;
      $gives = $coupon->gives;

      foreach ($gives as $give) {
        switch ($give->giveaway) {
          case 'discount_percent':
            $discounted_amount += ($discountable_amount*$give->value)/100;
            break;
          case 'discount_value':
            $discounted_amount += $give->value;
            break;
          case 'cashback_percent_value':
            $cashback_amount+= ceil(($discountable_amount*$give->value)/100);
            break;
          case 'points_value':
            $cashback_amount+= $give->value;
            break;
          case 'meal':
            $new_order_item = new ItemOrder;
            $new_order_item->order_id = $order->id;
            $item = Item::where('itemable_id',$give->value)->where('itemable_type','App\Models\Meal')->first();
            $new_order_item->item_id = $item>id;
            $dependency = CouponGiveAway::where('giveaway','quantity')->where('group',$give->group)->first();
            $new_order_item->quantity = $dependency->value;
            $new_order_item->unit_price = 0;
            $new_order_item->save();
            break;
          case 'combo':
            $new_order_item = new ItemOrder;
            $new_order_item->order_id = $order->id;
            $item = Item::where('itemable_id',$give->value)->where('itemable_type','App\Models\Combo')->first();
            $new_order_item->item_id = $item>id;
            $dependency = CouponGiveAway::where('giveaway','quantity')->where('group',$give->group)->first();
            $new_order_item->quantity = $dependency->value;
            $new_order_item->unit_price = 0;
            $new_order_item->save();
            break;
          case 'goody':
            $new_order_item = new ItemOrder;
            $new_order_item->order_id = $order->id;
            $item = Item::where('itemable_id',$give->value)->where('itemable_type','App\Models\Goody')->first();
            $new_order_item->item_id = $item->id;
            $dependency = CouponGiveAway::where('giveaway','quantity')->where('group',$give->group)->first();
            $new_order_item->quantity = $dependency->value;
            $new_order_item->unit_price = 0;
            $new_order_item->save();
            break;
          case 'item':
            $new_order_item = new ItemOrder;
            $new_order_item->order_id = $order->id;
            $new_order_item->item_id = $give->value;
            $dependency = CouponGiveAway::where('giveaway','quantity')->where('group',$give->group)->first();
            $new_order_item->quantity = $dependency->value;
            $new_order_item->unit_price = 0;
            $new_order_item->save();
            break;
          default:
            break;
        }
      }
      if($max_cashback_amount<0)
        $cashback_buff = $cashback_amount ;
      else if($max_cashback_amount<$cashback_amount)
        $cashback_buff = $max_cashback_amount ;
      else
        $cashback_buff = $cashback_amount;

      if($max_discount_amount<0)
        $discount = $discounted_amount ;
      else if($max_discount_amount<$discounted_amount)
        $discount = $max_discount_amount ;
      else
        $discount = $discounted_amount;

    }

    $data['success'] = 1;
    $data['message'] = 'Coupon Applied Successfully';
    $data['discount'] = $discount;
    $data['cashback_buff'] = $cashback_buff;
    $logger->log('Coupon Data :  '.json_encode($data));
    return $data;

  }

  public function applyCoupon($inputs)
  {
    $coupon = Coupon::where('code', $inputs->get('coupon_code') )->first();

    if(!$inputs->get('coupon_code')) throw new InvalidCountException('Please enter coupon');

    if(!$coupon) throw new InvalidCouponException('Invalid Coupon');

    $is_coupon_valid = $this->checkCoupon($inputs);

    if($is_coupon_valid['success']==1)
    {
      return $is_coupon_valid;
    }
    else
      if(env('COUPON_DEBUG'))
      throw new InvalidCouponException($is_coupon_valid['message']);
      else
      throw new InvalidCouponException('Coupon is Either Invalid Or Expired');
  }


  public function updateOrderLog($order_id,$request)
  {
    $orderLog = new LogOrder;
    $order = Order::find($order_id);
    $orderLog->user_id = auth()->user()->id;
    $orderLog->order_id = $order->id;
    $orderLog->comment = $request->get('comment');
    $orderLog->status_from_id = $order->status;
    $orderLog->status_to_id = $request->get('status');
    $orderLog->save();
  }

  public function addOrderLog($order_id,$previous_status,$current_status,$comment)
  {
    $orderlog = new LogOrder;
    if(auth()->user())
    $orderlog->user_id = auth()->user()->id;
    else
    $orderlog->user_id = config('bueno.default_auth.user_id');
    $orderlog->order_id = $order_id;
    $orderlog->comment = $comment;
    $orderlog->status_from_id = $previous_status['order'];
    $orderlog->status_to_id = $current_status['order'];
    $orderlog->payment_status_from = $previous_status['payment'];
    $orderlog->payment_status_to = $current_status['payment'];
    $orderlog->save();
  }

  public function updatePaymentStatus($order_id,$status)
  {
    $payment = Payment::where('order_id',$order_id)->get()->first();
    $payment->status = $status;
    $payment->save();
  }

  public function updateOrderPayment($order_id, $response = null)
  {
    $order = Order::find($order_id);

    $order->update([
      'status'  => 2
    ]);

    $order->paymentInfo->update([
      'status' => 2,
      'transaction_details' => $response
    ]);

    $this->deleteUserCart($order->user);


    $response = Segment::track([
        "userId"     => auth()->user()->id,
        "event"      => "Order Successful",
        "properties" => [
            "order_id" => $order->id,
            "timestamp" => Carbon::now()->timestamp
        ]
    ]);

    LaravelLog::info('Segment .'.$response);

    event(new OrderWasCreatedByUser($order));

    return $order;
  }

  public function dispatchOrder($order_no)
  {
    $order = Order::where('order_no', $order_no)->where('status', 7)->first();


    if(!$order) throw new ModelNotFoundException('Order not found !');

    $orderLog = new LogOrder;
    $orderLog->user_id = auth()->user()->id;
    $orderLog->order_id = $order->id;
    $orderLog->comment = 'Order is Marked Dispatched';
    $orderLog->status_from_id = $order->status;
    $orderLog->status_to_id = 3;
    $order->update([
        'status' => 3
    ]);
    $orderLog->save();

    event(new OrderWasDispatched($order));
//    event(new OrderStatusWasUpdated($order));


    return $order;

  }

  public function packOrder($order_no)
  {
    $order = Order::where('order_no', $order_no)->where('status', 2)->first();

    if(!$order) throw new ModelNotFoundException('Order not found !');

    $orderLog = new LogOrder;
    $orderLog->user_id = auth()->user()->id;
    $orderLog->order_id = $order->id;
    $orderLog->comment = 'Order is Packed';
    $orderLog->status_from_id = $order->status;
    $orderLog->status_to_id = 7;
    $orderLog->save();

    $order->update([
        'status' => 7
    ]);

    event(new OrderStatusWasUpdated($order));

  }

  public function updateOrderPaymentForCod($order_id)
  {
    $order = Order::find($order_id);

    $order->update([
        'status'  => 2
    ]);

    $order->paymentInfo->update([
        'status' => 2
    ]);
  }

  public function deleteUserCart($user)
  {
    foreach($user->cartItems as $cart_item)
    {
      $cart_item->delete();
    }
  }

  /**
   *
   */
  public function createOrderInvoice($new_order)
  {

    //Items

    foreach($new_order->items as $item)
    {
      if($item->itemable_type!='App\Model\Goody')
      {
        $invoice = new OrderInvoice;

        $invoice->order_id = $new_order->id;

        $invoice->charge_for = $item->itemable->name;

        $invoice->quantity = $item->pivot->quantity;

        $invoice->amount = $item->pivot->unit_price * $item->pivot->quantity;

        $invoice->save();
      }
      else
      {
        $invoice = new OrderInvoice;

        $invoice->order_id = $new_order->id;

        $invoice->charge_for = $item->itemable->name;

        $invoice->quantity = $item->pivot->quantity;

        $invoice->amount = 0;

        $invoice->save();
      }
    }


  }

  public function getAllPaymentStatus()
  {
    return PaymentStatus::get();
  }

  public function couponGive($coupon,$couponChecks)
  {
    $discount = 0;
    $cashback = 0;
    $items = null;
    $item_count = 0;
    $discountable_amount = isset($couponChecks['discount_applicable_on']) ? $couponChecks['discount_applicable_on'] : 0;
    $max_discount_amount = isset($couponChecks['max_discount_amount']) ? $couponChecks['max_discount_amount'] : 0;
    $max_cashback_amount = isset($couponChecks['max_cashback_amount']) ? $couponChecks['max_cashback_amount'] : 0;
    $gives = $coupon->gives;
    foreach ($gives as $give) {
      switch ($give->giveaway) {
        case 'discount_percent':
          $discount += ($discountable_amount*$give->value)/100;
          break;
        case 'discount_value':

          $discount += $give->value;
          break;

        case 'cashback_percent_value':

          $cashback += ($discountable_amount*$give->value)/100;

          break;
        case 'points_value':

          $cashback += $give->value;

          break;

        case 'goody':
          $item = Item::where('itemable_id',$give->value)->where('itemable_type','App\Models\Goody')->first();
          $items[$item->id]['name'] = $item->itemable->name;
          $quantity = CouponGiveAway::where('group',$give->group)->where('giveaway','quantity')->first();
          $items[$item->id]['quantity'] = $quantity->value;
          switch($item->itemable_type)
          {
            case 'App\Models\Meal'; $items[$item->id]['image_url'] = route('photo.meals',$item->itemable->display_pic_url); break;
            case 'App\Models\Combo'; $items[$item->id]['image_url'] = route('photo.combos',$item->itemable->display_pic_url); break;
            case 'App\Models\Goody'; $items[$item->id]['image_url'] = route('photo.goody',$item->itemable->image_url); break;
          }
          $item_count++;
          break;

        case 'meal':
          $item = Item::where('itemable_id',$give->value)->where('itemable_type','App\Models\Meal')->first();
          $items[$item->id]['name'] = $item->itemable->name;
          $quantity = CouponGiveAway::where('group',$give->group)->where('giveaway','quantity')->first();
          $items[$item->id]['quantity'] = $quantity->value;
          switch($item->itemable_type)
          {
            case 'App\Models\Meal'; $items[$item->id]['image_url'] = route('photo.meals',$item->itemable->display_pic_url); break;
            case 'App\Models\Combo'; $items[$item->id]['image_url'] = route('photo.combos',$item->itemable->display_pic_url); break;
            case 'App\Models\Goody'; $items[$item->id]['image_url'] = route('photo.goody',$item->itemable->image_url); break;
          }
          $item_count++;
          break;

        case 'combo':
          $item = Item::where('itemable_id',$give->value)->where('itemable_type','App\Models\Combo')->first();
          $items[$item->id]['name'] = $item->itemable->name;
          $quantity = CouponGiveAway::where('group',$give->group)->where('giveaway','quantity')->first();
          $items[$item->id]['quantity'] = $quantity->value;
          switch($item->itemable_type)
          {
            case 'App\Models\Meal'; $items[$item->id]['image_url'] = route('photo.meals',$item->itemable->display_pic_url); break;
            case 'App\Models\Combo'; $items[$item->id]['image_url'] = route('photo.combos',$item->itemable->display_pic_url); break;
            case 'App\Models\Goody'; $items[$item->id]['image_url'] = route('photo.goody',$item->itemable->image_url); break;
          }
          $item_count++;
          break;

        case 'item':
          $item = Item::find($give->value);
          $items[$item->id]['name'] = $item->itemable->name;
          $quantity = CouponGiveAway::where('group',$give->group)->where('giveaway','quantity')->first();
          $items[$item->id]['quantity'] = $quantity->value;
          switch($item->itemable_type)
          {
            case 'App\Models\Meal'; $items[$item->id]['image_url'] = route('photo.meals',$item->itemable->display_pic_url); 
            $items[$item->id]['type']='Meal';break;
            case 'App\Models\Combo'; $items[$item->id]['image_url'] = route('photo.combos',$item->itemable->display_pic_url); 
            $items[$item->id]['type']='Combo';break;
            case 'App\Models\Goody'; $items[$item->id]['image_url'] = route('photo.goody',$item->itemable->image_url); 
            $items[$item->id]['type']='Goody';break;
          }
          break;

        default:

          break;
      }
    }

    if($discount>=$max_discount_amount && $max_discount_amount>0)
    {
      $discount = $max_discount_amount;
    }

    if($cashback>=$max_cashback_amount && $max_cashback_amount>0)
    {
      $cashback = $max_cashback_amount;
    }

    $cashback = ceil($cashback);

    return [
      'discount' => $discount,
      'cashback' => $cashback,
      'items' => $items,
    ];
  }

  public function newOrderInvoice($id, $string, $amount, $quantity = 0)
  {
    $invoice = new OrderInvoice;

    $invoice->order_id = $id;

    $invoice->charge_for = $string;

    $invoice->amount = $amount ;

    $invoice->quantity = $quantity;

    $invoice->save();
  }

  public function applyCouponApi($inputs)
  {
    $coupon = Coupon::where('code', $inputs['coupon_code'] )->first();

    $source = OrderSource::where('name', $inputs['source_id'])->first();

    if(!$source)
    {
      $source = OrderSource::create([
        'name' => $inputs['source_id']
      ]);
    }

    $inputs['source_id']  = $source->id;

    if(!$coupon) throw new InvalidCouponException('Invalid Coupon');

    $is_coupon_valid = $this->checkCouponApi($inputs);

    if($is_coupon_valid['success']==1)
    {
      return $is_coupon_valid;
    }
    else
      throw new InvalidCouponException('Coupon is Either Invalid Or Expired '. $is_coupon_valid['message']);
  }

  public function getNewOrders($kitchen_id = null)
  {
    if($kitchen_id==null)
    {$orders = Order::where('status', 2)->get();
      Order::where('status', 2)->update([
          'is_printed' => 1
      ]);
    }

    else
    {$orders = Order::where('status',2)->where('kitchen_id',$kitchen_id)->get();
      Order::where('status', 2)->where('kitchen_id',$kitchen_id)->update([
          'is_printed' => 1
      ]);
    }

    return $orders;
  }

  public function getPackedOrders($kitchen_id = null)
  {

    $orders = new Order;
    $orders = $orders->where('status',7);
    if($kitchen_id!=null)
    {
      $orders = $orders->where('kitchen_id',$kitchen_id)->get();
    }
    else{
      $orders = $orders->get();
    }

      return $orders;
  }

  public function getDispatchedOrders($kitchen_id = null)
  {
    if($kitchen_id==null)
    {$orders = Order::where('status', 3)->get();
    }

    else
    {$orders = Order::where('status',3)->where('kitchen_id',$kitchen_id)->get();
    }

    return $orders;
  }

  public function getCancelledOrders($kitchen_id = null, $inputs)
  {
    $keyword = isset($inputs['keyword']) ? $inputs['keyword'] : '';
    $sortBy = isset($inputs['sortBy']) ? $inputs['sortBy'] : '';
    $direction = isset($inputs['direction']) && $inputs['direction'] == 'asc' ? "asc" : "desc" ;

    $orders = new Order;

    $orders = $orders->where('status',6);

    if($keyword!='')
    {
      $users = new User;

      $users = $users
          ->whereRaw("(CONCAT(first_name,' ',last_name) like '%".$keyword."%')");

      $users = $users->orWhere('email','LIKE',$keyword);

      $users = $users->orderBy('created_at','desc');

      $users = $users->take(20)->get();

      $user_ids = $users->pluck('id')->toArray();

      if(count($user_ids))
      {
        $orders = $orders->whereIn('user_id', $user_ids);
        if($kitchen_id) $orders = $orders->where('kitchen_id',$kitchen_id);
      }
    }


    $orders = $orders->orWhere('order_no', 'LIKE', '%'.$keyword);

    $orders = $orders->where('status',6);

    if($kitchen_id) $orders = $orders->where('kitchen_id',$kitchen_id);

    $orders = $orders->orWhere('user_phone', 'LIKE',$keyword);

    switch (1) {
      case 1:
      $orders = $orders->orWhere('status','6');
    }
    
    if($kitchen_id) $orders = $orders->where('kitchen_id',$kitchen_id);


    $orders = $this->sortOrders($orders, $sortBy, $direction);
    if($kitchen_id) $orders = $orders->where('kitchen_id',$kitchen_id);


    return $orders->paginate(30);
  }

  public function checkIfOrderExistsByResourceId($inputs)
  {
    $source =  OrderSource::where('name', $inputs['tag'])->first();

    $source_id = $source ? $source->id : 0;

    $order = Order::where("resource_order_no", $inputs['order_no'])->where('source_id', $source_id)->first();

    if($order) throw new CheckoutValidationException('This Order number already exists in database.');
  }

  public function getOrdersListing($inputs,$kitchen_id=null)
  {
    $keyword = isset($inputs['keyword']) ? $inputs['keyword'] : '';
    $sortBy = isset($inputs['sortBy']) ? $inputs['sortBy'] : '';
    $direction = isset($inputs['direction']) && $inputs['direction'] == 'asc' ? "asc" : "desc" ;

    $orders = new Order;

    if($keyword!='')
    {
      $users = new User;

      $users = $users
          ->whereRaw("(CONCAT(first_name,' ',last_name) like '%".$keyword."%')");

      $users = $users->orWhere('email','LIKE',$keyword);

      $users = $users->orderBy('created_at','desc');

      $users = $users->take(20)->get();

      $user_ids = $users->pluck('id')->toArray();

      if(count($user_ids))
      {
        $orders = $orders->whereIn('user_id', $user_ids);
        if($kitchen_id) $orders = $orders->where('kitchen_id',$kitchen_id);
      }
    }


    $orders = $orders->orWhere('order_no', 'LIKE', '%'.$keyword);
    if($kitchen_id) $orders = $orders->where('kitchen_id',$kitchen_id);

    $orders = $orders->orWhere('user_phone', 'LIKE',$keyword);

    switch ($keyword) {
      case 'Cancelled':
      $orders = $orders->orWhere('status','6');

        break;
      case 'In Kitchen':
      $orders = $orders->orWhere('status','2');

        break;
      case 'Delivered':
      $orders = $orders->orWhere('status','4');

        break;
      case 'Dispatched':
      $orders = $orders->orWhere('status','3');

        break;
      case 'Packed':
      $orders = $orders->orWhere('status','7');

        break;
    }
    if($kitchen_id) $orders = $orders->where('kitchen_id',$kitchen_id);


    $orders = $this->sortOrders($orders, $sortBy, $direction);
    if($kitchen_id) $orders = $orders->where('kitchen_id',$kitchen_id);


    return $orders->paginate(30);
  }


  /**
   * @param $orders
   * @param $sortBy
   * @param $direction
   * @return mixed
   */
  public function sortOrders($orders, $sortBy, $direction)
  {

    switch ($sortBy) {

        case 'order_date':
         $orders = $orders->orderBy('created_at', $direction );
          break;

        case 'order_no' :
          $orders = $orders->orderBy('id', $direction);
           break;

        case 'status' :
          $orders = $orders->orderBy('status', $direction);
           break;
       
        default:
          $orders = $orders->orderBy('created_at', $direction );
          break;
      }

      return $orders;
  }


  public function markOrdersAsSettled($order_ids)
  {
    $orders = Order::whereIn('id', $order_ids)->get();

    $logger = new DebugLogger;

    foreach($orders as $order)
    {
      try
      {

        $logger->log('Update Order Status Order ID : '.$order->id.' From : '.$order->status.' To : 4');
        $order->update([
            'status'  => 4
        ]);
        $logger->log('Update Payment Status Order ID : '.$order->id.' From : '.$order->paymentInfo->status.' To : 3');
        if($order->paymentInfo){
          $order->paymentInfo->update([
              'status' => 3
          ]);
        }

        event(new OrderWasSettled($order));
      }
      catch(\Exception $e)
      {
        $logger->log('Update Order ID : '.$order->id.' Failed '.$e->getMessage());
      }

    }
  }

  public function getInKitchenOrders($string,$kitchen_id="all")
  {
    if($string=='today')
    {
      if($kitchen_id!="all")
      $orders = $this->getAllOrdersByDays('0',$kitchen_id);
      else
      $orders = $this->getAllOrdersByDays('0');
    }
    else
    {
      if($kitchen_id!="all")
        $orders = Order::where('kitchen_id',$kitchen_id)->where('status',2)->get();
      else
        $orders = Order::where('status',2)->get();
    }

    return $orders;
  }
  public function generateReports($orders)
  {
    foreach ($orders as $order) {

    }
  }

  public function getOrdersAfterDate($date,$end)
  {
    $orders = Order::where('status', 4)->where('created_at', '>=', $date)->where('created_at', '<=', $end)->get();
    $orders = $orders->filter(function($order){
      return $order->paymentInfo->status==2;
    });
    return $orders;
  }

  // Order Backup

  public function customBackup($amount,$start,$end)
  {
    $start = new DateTime($start);
    $end = new DateTime($end);
    while($amount>0)
    {
      echo $amount.'  , ';
      $user = User::orderByRaw("RAND()")->first();
      if($user)
      {

        if($user->agree_terms!=1)
        $new_user = $this->backupUser($user);
        else
        $new_user = $user;

        if(!$new_user)
        {
          continue;
        }

        if($new_user->addresses->count()==0)
        {
          $address = Address::orderByRaw("RAND()")->first();
          $new_address = $this->newUserAddress($new_user->id,[
          'area_id' => $address->area->id,
          'address' => $address->address
          ]);
        }
        else
        {
          $new_address = $new_user->addresses->first();
        }

        $order = $this->getOrderByDate($start,$end,$new_address->area_id);
        if($order && $order->paymentInfo && $order->items && $order->logs && $order->invoice && $order->paymentInfo->amount)
        {

        $tax = $order->invoice->sum(function($charge)
          {
            return in_array($charge->charge_for, ['Service Tax','VAT','Delivery Charge','Packaging Charge','Service Charge']) ? $charge->amount : 0;
          });
          $amount -= $order->paymentInfo ? $order->paymentInfo->amount - $tax : 0;
          $new_order = $this->createOrderBackup($new_user,$order,$new_address);
          LaravelLog::info('(DB) User Id (t-f): '.$new_user->id.'-'.$user->id.' Order Id (t-f): '.$new_order->id.'-'.$order->id.' Amount : '.$amount);
        }
        else
        {
          $amount-=0;
        }
      }
    }
  }

  public function backupUser($user)
  {
    $phone1 = substr($user->phone,0,1);
    $phone9 = strrev(substr($user->phone,1));
    $exist = $user;
    $try = 10;
    while($exist && $try>0)
    {
    $phone9 = str_shuffle($phone9);
    $phone = $phone1.$phone9;
    $exist = User::where('phone',$phone)->first();
    $try--;
    }
    if($exist==null)
    {
      $new_user = User::create([
      'first_name' => $user->first_name,
      'last_name' => $user->last_name,
      'phone' => $phone,
      'group_id' => 2,
      'gender' => rand(0,2),
      'membership_id' => 1,
      'status' => 1
      ]);
      $new_user->created_at = $user->created_at;
      $new_user->agree_terms = 1;
      $new_user->password = str_shuffle($user->password);
      $new_user->referral_code = substr($new_user->first_name,0,4).$new_user->id;
      $new_user->save();
      return $new_user;
    }
    return false;
  }

  public function getUserAddressByArea($area_id)
  {
    return Address::where('area_id',$area_id)->get()->random();
  }

  public function getLoyalOrders($start,$end)
  {
    $orders =  Order::where('created_at','>=',$start)->where('created_at','<=',$end)->whereNull('coupon_id')->whereStatus('4')->whereNotIn('source_id',[3,6,8])->get();

    $orders = $orders->filter(function($order){
      return $order->paymentInfo && $order->paymentInfo->status=3 ? true : false;
    });

    return $orders;
  }

  public function creditReward($order)
  {
    $user = User::find($order->user_id);

    if($user==null)
    {
      return 'No User Found';
    }

    $create_date_time = $order->created_at;

    $orders = $user->confirmedOrders();

    $orders = $orders->filter(function ($order) use($create_date_time){
      return $order->created_at < $create_date_time;
    });

    $membership = Membership::where('min','<=',$orders->count())->orderBy('min','desc')->first();

    $tax =  $order->invoice->sum(function($charge)
          {
            return in_array($charge->charge_for, ['Service Tax','VAT','Delivery Charge','Packaging Charge','Service Charge']) ? $charge->amount : 0;
          });

    $rewards = $membership->loyalty_points * 0.01 * ($order->paymentInfo->amount - $tax);

    $user->points += $rewards;

    $user->save();

    return 'User Id : '.$user->id.'. Order Id : '.$order->id.'. Points Credited ('.$membership->name.') : '.$rewards;

  }

  public function getOrderByDate($start,$end,$area_id)
  {
    $order =  Order::where('created_at','>=',$start)
              ->where('created_at','<=',$end)
              ->where('delivery_area_id',$area_id)
              ->whereStatus(4)
              ->get();
    if($order->count())
    {
      return $order->random();
    }
    else
    {
      return Order::where('created_at','>=',$start)
              ->where('created_at','<=',$end)
              ->whereStatus(4)
              ->get()->random();
    }
  }

  public function createOrderBackup($user,$order,$address)
  {
    //Order Info - Items ,  payments , Log , Jooleh
    //Order

    $new_order = Order::create([
      'user_id' => $user->id,
      'source_id' => $order->source_id,
      'delivery_area_id' => $address->area->id,
      'delivery_address' => $address->address,
      'coupon_id' => $order->coupon_id,
      'user_phone' => $user->phone,
      'kitchen_id' => $address->area->firstKitchen() ? $address->area->firstKitchen()->id : 1,
      'redeem_points' => $order->redeem_points,
      'ngo_id' =>$order->ngo_id,
      'payment_mode_id' => $order->payment_mode_id,
      'resource_order_no' => str_shuffle($order->resource_order_no),
      'latitude' => $order->latitude,
      'longitude' => $order->longitude,
      'is_printed' => 1,
      'status' => 4
      ]);

    $minutes_to_add = rand(0,10);
    $days_to_add = rand(0,3);

    $created_at = $order->created_at->sub(new DateInterval('P'.$days_to_add.'DT' . $minutes_to_add . 'M'));

    $new_order->created_at = $created_at;

    $new_order->order_no = $created_at->format('dmy').$new_order->id;

    $new_order->updated_at = $order->updated_at->sub(new DateInterval('P'.$days_to_add.'DT' . $minutes_to_add . 'M'));

    //Payment


    $new_payment  = Payment::create([
      'order_id' => $new_order->id,
      'status' => 3,
      'amount' => $order->paymentInfo->amount,
      'transaction_id' => str_shuffle($order->paymentInfo->transaction_id),
      'payment_mode_id' => $order->paymentInfo->payment_mode_id,
      'cashback_buff' => $order->paymentInfo->cashback_buff,
      'settlement_amount' => $order->paymentInfo->settlement_amount,
      'transaction_details' => $order->paymentInfo->transaction_details
      ]);
    $new_payment->created_at = $order->paymentInfo->created_at->sub(new DateInterval('P'.$days_to_add.'DT' . $minutes_to_add . 'M'));
    $new_payment->updated_at = $order->paymentInfo->updated_at->sub(new DateInterval('P'.$days_to_add.'DT' . $minutes_to_add . 'M'));
    $new_payment->save();

    //Order Invoice
    foreach ($order->invoice as $invoice) {
      $new_invoice = OrderInvoice::create([
        'order_id' => $new_order->id,
        'quantity' => $invoice->quantity,
        'charge_for' => $invoice->charge_for,
        'amount' => $invoice->amount
        ]);
      $new_invoice->created_at = $invoice->created_at->sub(new DateInterval('P'.$days_to_add.'DT' . $minutes_to_add . 'M'));
      $new_invoice->updated_at = $invoice->updated_at->sub(new DateInterval('P'.$days_to_add.'DT' . $minutes_to_add . 'M'));
      $new_invoice->save();
    }

    //Order Log
    foreach ($order->logs as $log) {
      $new_log = LogOrder::create([
        'order_id' => $new_order->id,
        'user_id' => $log->user_id,
        'comment' => $log->comment,
        'status_to_id' => $log->status_to_id,
        'status_from_id' => $log->status_from_id,
        ]);
      $new_log->created_at = $log->created_at->sub(new DateInterval('P'.$days_to_add.'DT' . $minutes_to_add . 'M'));
      $new_log->updated_at = $log->updated_at->sub(new DateInterval('P'.$days_to_add.'DT' . $minutes_to_add . 'M'));
      $new_log->save();
    }

    // Order Items

    foreach ($order->items as $item) {
      $new_item = ItemOrder::create([
        'order_id' => $new_order->id,
        'item_id' => $item->id,
        'quantity' => $item->pivot->quantity,
        'unit_price' => $item->pivot->unit_price,
        ]);
      $new_item->created_at = $item->created_at->sub(new DateInterval('P'.$days_to_add.'DT' . $minutes_to_add . 'M'));
      $new_item->updated_at = $item->updated_at->sub(new DateInterval('P'.$days_to_add.'DT' . $minutes_to_add . 'M'));
      $new_item->save();
    }

    $new_order->is_sent_confirmation_email = 1;

    $new_order->save();

    return $new_order;

  }

  public function updateCancelReason($inputs)
  {
    $cancel_reason = CancelReason::find($inputs['id']);

    $cancel_reason->update([
      'reason' => $inputs['reason']
    ]);

    return $cancel_reason;
  }

  public function createCancelReason($inputs)
  {
    return CancelReason::create([
      'reason'  => $inputs['reason']
    ]);
  }

  public function fixOrderInvoice($inputs)
  {
    $source =  OrderSource::where('name', $inputs['tag'])->first();
    $order = Order::where('resource_order_no',$inputs['order_no'])->where('source_id',$source->id)->first();
    OrderInvoice::where('order_id',$order->id)->delete();
    $this->createOrderInvoiceApi($order,$inputs);
    return $order;
  }

  public function revertOrder()
  {

    // User::where('agree_terms',1)->chunk(500,function($users)
    //   {
    //     $user_ids = $users->pluck('id')->toArray();
        
    //     DB::table('orders')->whereIn('user_id',$user_ids)->update(array('is_sent_confirmation_email' => 1));
        
    //   });
    LaravelLog::info(Order::where('is_sent_confirmation_email',1)->count().' Total Order');
    Order::where('is_sent_confirmation_email',1)->chunk(5000, function($orders)
        {
          $order_ids = $orders->pluck('id')->toArray();
          DB::table('payments')->whereIn('order_id', $order_ids)->delete();
          DB::table('order_invoice')->whereIn('order_id', $order_ids)->delete();
          DB::table('item_order')->whereIn('order_id', $order_ids)->delete();
          DB::table('log_order')->whereIn('order_id', $order_ids)->delete();
          DB::table('orders')->whereIn('id', $order_ids)->delete();
          LaravelLog::info(count($order_ids).' Orders Deleted');
        });
    LaravelLog::info(User::where('agree_terms',1)->count().' Total Users');
    User::where('agree_terms',1)->chunk(5000,function($users)
      {
        $user_ids = $users->pluck('id')->toArray();
        DB::table('users')->whereIn('id',$user_ids)->delete();
        DB::table('orders')->whereIn('id',$user_ids)->delete();
        DB::table('address_user')->whereIn('id',$user_ids)->delete();
        LaravelLog::info(count($user_ids).' Users Deleted');
      });

    LaravelLog::info(Order::where('is_sent_confirmation_email',1)->count().' Total Order');
    LaravelLog::info(User::where('agree_terms',1)->count().' Total Users');

  }

}
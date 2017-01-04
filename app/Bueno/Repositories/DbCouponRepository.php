<?php namespace Bueno\Repositories;

use App\Models\Item;
use App\Models\User;
use App\Models\Goody;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\CheckCoupon;
use App\Models\CouponGiveaway;
use DateTime;


class DbCouponRepository  {
  
  public function getAllGoodies()
  {
    return Goody::get();
  }

  public function getGoodyById($id)
  {
    return Goody::find($id);
  }

  public function couponGives($inputs)
  {
    $discount_amount_value = isset($inputs['discount_amount_value']) ? $inputs['discount_amount_value']: null;
    $discount_percent_value = isset($inputs['discount_percent_value']) ? $inputs['discount_percent_value']: null;
    $goodies = isset($inputs['goodies']) ? $inputs['goodies']: null;
    $cashback_percent_value = isset($inputs['cashback_percent_value']) ? $inputs['cashback_percent_value']: null;
    $cashback_value = isset($inputs['cashback_value']) ? $inputs['cashback_value']: null;
    $points_value = isset($inputs['points_value']) ? $inputs['points_value']: null;
    $give_meals = isset($inputs['give_meals']) ? $inputs['give_meals']: null;
    $give_combos = isset($inputs['give_combos']) ? $inputs['give_combos']: null;
    if($discount_amount_value==null && $discount_percent_value==null && $goodies==null && $cashback_percent_value==null && $cashback_value==null && $points_value==null && $give_meals==null && $give_combos==null)
    {
      return false;
    }
    return true;
  }

  public function deleteCouponById($id)
  {
    $coupon = Coupon::find($id);
    $order = Order::where('coupon_id',$coupon->id)->get();
    if($order->count()==0)
    {
      CheckCoupon::where('coupon_id',$coupon->id)->delete();
      CouponGiveaway::where('coupon_id',$coupon->id)->delete();
      $coupon->delete();
      return true;
    }
    else
    {
      return false;
    }
  }

  public function updateCoupon($id,$inputs)
  {
    $coupon = Coupon::find($id);
    $coupon_attributes = $this->getCouponAttributes($coupon);
    //Checks
    //Min Order Amount
    if($coupon_attributes['min_order_amount'])
      ($inputs['min_order_amount'] && $inputs['min_order_amount']!='') ? $this->updateCheckAttribute('min_order_amount',$inputs['min_order_amount'],$coupon) : $this->deleteCheckAttribute('min_order_amount',$coupon);
    else if($inputs['min_order_amount'] && $inputs['min_order_amount']!='')
    {
      $this->checkCouponAttribute($coupon->id,'min_order_amount',$inputs['min_order_amount']);
    }
    //Min Quantity Amount
    if($coupon_attributes['min_quantity'])
      ($inputs['min_quantity'] && $inputs['min_quantity']!='') ? $this->updateCheckAttribute('total_min_quantity',$inputs['min_quantity'],$coupon) : $this->deleteCheckAttribute('total_min_quantity',$coupon);
    else if($inputs['min_quantity'] && $inputs['min_quantity']!='')
    {
      $this->checkCouponAttribute($coupon->id,'total_min_quantity',$inputs['min_quantity']);
    }
    //Nth Order
    if($coupon_attributes['nth_order'])
      (isset($inputs['value_n']) && $inputs['value_n']!='') ? $this->updateCheckAttribute('nth_order',$inputs['value_n'],$coupon) : $this->deleteCheckAttribute('nth_order',$coupon);
    else if($inputs['value_n'] && $inputs['value_n']!='')
    {
      $this->checkCouponAttribute($coupon->id,'nth_order',$inputs['value_n']);
    }

    //Max Discount Amount
    if($coupon_attributes['max_discount_amount'])
      (isset($inputs['max_discount_amount']) && $inputs['max_discount_amount']!='') ? $this->updateCheckAttribute('max_discount_amount',$inputs['max_discount_amount'],$coupon) : $this->deleteCheckAttribute('max_discount_amount',$coupon);
    else if($inputs['max_discount_amount'] && $inputs['max_discount_amount']!='')
    {
      $this->checkCouponAttribute($coupon->id,'max_discount_amount',$inputs['max_discount_amount']);
    }

    //Max Cashback Amount
    if($coupon_attributes['max_cashback_amount'])
      (isset($inputs['max_cashback_amount']) && $inputs['max_cashback_amount']!='') ? $this->updateCheckAttribute('max_cashback_amount',$inputs['max_cashback_amount'],$coupon) : $this->deleteCheckAttribute('max_cashback_amount',$coupon);
    else if($inputs['max_cashback_amount'] && $inputs['max_cashback_amount']!='')
    {
      $this->checkCouponAttribute($coupon->id,'max_cashback_amount',$inputs['max_cashback_amount']);
    }

    //Sources

    //android
    if($coupon_attributes['android'])
      isset($inputs['android']) ?  1 : $this->deleteCheckSourceAttribute(1,$coupon);
    else if($inputs['android'])
    {
      $this->createCheckSourceAttribute(1,$coupon);
    }

    //ios
    if($coupon_attributes['ios'])
      isset($inputs['ios']) ? 4 : $this->deleteCheckSourceAttribute(4,$coupon);
    else if($inputs['ios'])
    {
      $this->createCheckSourceAttribute(4,$coupon);
    }

    //web
    if($coupon_attributes['web'])
      isset($inputs['web']) ? 7 : $this->deleteCheckSourceAttribute(7,$coupon);
    else if($inputs['web'])
    {
      $this->createCheckSourceAttribute(7,$coupon);
    }

    //ios
    if($coupon_attributes['quick'])
      isset($inputs['quick']) ? 5 : $this->deleteCheckSourceAttribute(5,$coupon);
    else if($inputs['quick'])
    {
      $this->createCheckSourceAttribute(5,$coupon);
    }

    //Categories

    $this->deleteMultipleGroupCheckAttribute('category',$coupon);

    
    if($inputs['categories'])
    {
      foreach ($inputs['categories'] as $category) {
        $this->checkCouponItem($coupon->id,$category['id'],$category['quantity'],'category');
      }
    }

    //Cuisines

    $this->deleteMultipleGroupCheckAttribute('cuisine',$coupon);

    if($inputs['cuisines'])
    {
      foreach ($inputs['cuisines'] as $cuisine) {
        $this->checkCouponItem($coupon->id,$cuisine['id'],$cuisine['quantity'],'cuisine');
      }
    }

    //Items

    $this->deleteMultipleGroupCheckAttribute('item',$coupon);

    if($inputs['meals'])
    {
      foreach ($inputs['meals'] as $item) {
        $this->checkCouponItem($coupon->id,$item['id'],$item['quantity'],'item');
      }
    }


    //One Time
    if($coupon_attributes['one_time'])
      isset($inputs['one_time']) ? $this->updateCheckAttribute('one_time',1,$coupon) : $this->deleteCheckAttribute('one_time',$coupon);
    else if($inputs['one_time'])
    {
      $this->checkCouponAttribute($coupon->id,'one_time',1);
    }

    //Kitchens

    $this->deleteMultipleCheckAttribute('on_kitchen',$coupon);
    $kitchen_specific = $inputs['kitchens'];
    if($kitchen_specific!=null)
    {
      foreach ($kitchen_specific as $key => $value) {
        $this->checkCouponAttribute($coupon->id,'on_kitchen',$value);
      }
    }

    $this->deleteMultipleCheckAttribute('on_payment_gateway',$coupon);
    $payment_modes = $inputs['payment_modes'];
    if($payment_modes!=null)
    {
      foreach ($payment_modes as $key => $value) {
        $this->checkCouponAttribute($coupon->id,'on_payment_gateway',$value);
      }
    }

    $this->deleteMultipleCheckAttribute('on_area',$coupon);
    $area_specific = $inputs['areas'];
    if($area_specific!=null)
    {
      foreach ($area_specific as $key => $value) {
        $this->checkCouponAttribute($coupon->id,'on_area',$value);
      }
    }

    $this->deleteMultipleCheckAttribute('on_city',$coupon);
    $city_specific = $inputs['cities'];
    if($city_specific!=null)
    {
      foreach ($city_specific as $key => $value) {
        $this->checkCouponAttribute($coupon->id,'on_city',$value);
      }
    }

    $this->deleteMultipleCheckAttribute('on_state',$coupon);
    $state_specific = $inputs['states'];
    if($state_specific!=null)
    {
      foreach ($state_specific as $key => $value) {
        $this->checkCouponAttribute($coupon->id,'on_state',$value);
      }
    }

    $this->deleteMultipleCheckAttribute('for_user',$coupon);
    $user_specific = $inputs['users'];
    if($user_specific!=null)
    {
      foreach ($user_specific as $key => $value) {
        $this->checkCouponAttribute($coupon->id,'for_user',$value);
      }
    }

    $this->deleteMultipleCheckAttribute('by_user',$coupon);
    $admin_allowed = $inputs['admins'];
    if($admin_allowed!=null)
    {
      foreach ($admin_allowed as $key => $value) {
        $this->checkCouponAttribute($coupon->id,'by_user',$value);
      }
    }

    $from = $coupon_attributes['from'];
    $to = $coupon_attributes['to'];
    $start = $inputs['start'];
    if($start!=null)
    {
      if($from)
        $this->updateCouponFromDate($start,$coupon);
      else
        $this->createCouponFromDate($start,$coupon);
    }
    elseif($from)
    {
      $this->deleteCouponFromDate($coupon);
    }

    $end = $inputs['end'];
    if($end!=null)
    {
      if($to)
        $this->updateCouponToDate($end,$coupon);
      else
        $this->createCouponToDate($end,$coupon);
    }
    elseif($to)
    {
      $this->deleteCouponToDate($coupon);
    }
    // Gives

    //Discount
    if($coupon_attributes['discount_percent'])
      $inputs['discount_percent_value'] ? $this->updateGiveAttribute('discount_percent',$inputs['discount_percent_value'],$coupon) : $this->deleteGiveAttribute('discount_percent',$coupon);
    else if($inputs['discount_percent_value'])
    {
      $this->giveCouponAttribute($coupon->id,'discount_percent',$inputs['discount_percent_value']);
    }

    if($coupon_attributes['discount_value'])
      $inputs['discount_amount_value'] ? $this->updateGiveAttribute('discount_value',$inputs['discount_amount_value'],$coupon) : $this->deleteGiveAttribute('discount_value',$coupon);
    else if($inputs['discount_amount_value'])
    {
      $this->giveCouponAttribute($coupon->id,'discount_value',$inputs['discount_amount_value']);
    }
    //Cashback
    if($coupon_attributes['cashback_percent_value'])
      $inputs['cashback_percent_value'] ? $this->updateGiveAttribute('cashback_percent_value',$inputs['cashback_percent_value'],$coupon) : $this->deleteGiveAttribute('cashback_percent_value',$coupon);
    else if($inputs['cashback_percent_value'])
    {
      $this->giveCouponAttribute($coupon->id,'cashback_percent_value',$inputs['cashback_percent_value']);
    }

    if($coupon_attributes['points_value'])
      $inputs['points_value'] ? $this->updateGiveAttribute('points_value',$inputs['points_value'],$coupon) : $this->deleteGiveAttribute('points_value',$coupon);
    else if($inputs['points_value'])
    {
      $this->giveCouponAttribute($coupon->id,'points_value',$inputs['points_value']);
    }

    // Give Items

    $this->deleteMultipleGroupGiveAttribute('item',$coupon);

    if($inputs['give_meals'])
    {
      foreach ($inputs['give_meals'] as $item) {
        $this->giveCouponItem($coupon->id,$item['id'],$item['quantity'],'item');
      }
    }


    $status = $inputs['status'];
    $coupon->status = $status;
    $description = $inputs['description'];
    $coupon->description = $description;
    $coupon->offer_text = $inputs['offer_text'];
    $coupon->is_offer = isset($inputs['is_offer']) ? $inputs['is_offer'] : 0;
    $coupon->save();
    return $coupon;
  }

  public function newGoody($inputs)
  {

    $goody = Goody::create($inputs->all());

    $new_image_check = $inputs->file('image_url');

    if(isset($new_image_check))
    {

    $file = $inputs->file('image_url');
    
    $filename = upload_file($file, 'goody');

    $goody->image_url = $filename;

    $goody->image_mime = $file->getClientMimeType();

    }

    $goody->save();

    $new_item['itemable_id'] = $goody->id;

    $new_item['itemable_type'] = 'App\Models\Goody';

    $item = Item::create($new_item);

    return $goody;
  }

  public function updateGoody($id,$inputs)
  {
    $goody = Goody::findOrFail($id);

    $new_image_check = $inputs->file('image_url');

    if(isset($new_image_check))
    {
    $file = $inputs->file('image_url');
    
    $filename = upload_file($file, 'goody');

    $goody->image_url = $filename;

    $goody->image_mime = $file->getClientMimeType();
    }

    return $goody->fill($inputs->all())->save();
  }

  // Coupons

  public function getAllCoupons()
  {
    return Coupon::get();
  }

  // Non Referral Coupons

  public function getAllNonReferralCoupons($inputs)
  {
    $keyword = isset($inputs['keyword']) ? strtolower($inputs['keyword']) : '';
    $sortBy = isset($inputs['sortBy']) ? $inputs['sortBy'] : '';
    $direction = isset($inputs['direction']) && $inputs['direction'] == 'asc' ? "asc" : "desc" ;

    $coupons = new Coupon;

    if($keyword == 'active')
    {
      $coupons = $coupons->where('status', 1)->whereNull('type');
    }
    else if($keyword == 'disabled')
    {
      $coupons = $coupons->where('status', 0)->whereNull('type');
    }
  
    $coupons = $coupons->orWhere('code', 'LIKE', '%' . $keyword . '%')->whereNull('type');

    $coupons = $this->sortCoupons($coupons, $sortBy, $direction);
  
    return $coupons->paginate(15);
  }

    /**
   * @param $orders
   * @param $sortBy
   * @param $direction
   * @return mixed
   */
  public function sortCoupons($coupons, $sortBy, $direction)
  {

    switch ($sortBy) {

        case 'id' :
          $coupons = $coupons->orderBy('id', $direction)->whereNull('type');
           break;

        case 'code' :
          $coupons = $coupons->orderBy('code', $direction)->whereNull('type');
           break;

        case 'status' :
          $coupons = $coupons->orderBy('status', $direction)->whereNull('type');
           break;
       
        default:
          $coupons = $coupons->orderBy('created_at', $direction)->whereNull('type');
          break;
      }

      return $coupons;
  }


  public  function  getCouponById($id)
  {
    return Coupon::find($id);
  }


  public function transformCouponInputs($inputs)
  {
    return [
        'coupon_code' => isset($inputs['coupon_code']) ? $inputs['coupon_code'] : null,
        'description' => isset($inputs['description']) ? $inputs['description'] : null,
        'status' => isset($inputs['status']) ? $inputs['status'] : null,
        'min_quantity' => (isset($inputs['min_quantity']) && $inputs['min_quantity']!='')  ? $inputs['min_quantity'] : null,
        'one_time' => isset($inputs['one_time']) ? $inputs['one_time'] : null,
        'android' => isset($inputs['android']) ? $inputs['android'] : null,
        'ios' => isset($inputs['ios']) ? $inputs['ios'] : null,
        'is_offer' => isset($inputs['is_offer']) ? $inputs['is_offer'] : 0,
        'web' => isset($inputs['web']) ? $inputs['web'] : null,
        'quick' => isset($inputs['quick']) ? $inputs['quick'] : null,
        'start' => isset($inputs['start']) ? $inputs['start'] : null,
        'end' => isset($inputs['end']) ? $inputs['end'] : null,
        'min_order_amount' => (isset($inputs['min_order_amount']) && $inputs['min_order_amount']!='') ? $inputs['min_order_amount'] : null,
        'max_discount_amount' => (isset($inputs['max_discount_amount']) && $inputs['max_discount_amount']!='') ? $inputs['max_discount_amount'] : null,
        'max_cashback_amount' => (isset($inputs['max_cashback_amount']) && $inputs['max_cashback_amount']!='') ? $inputs['max_cashback_amount'] : null,
        'value_n' => (isset($inputs['value_n']) && $inputs['value_n']!='') ? $inputs['value_n'] : null,
        'kitchens' => isset($inputs['kitchens']) ? $inputs['kitchens'] : null,
        'payment_modes' => isset($inputs['payment_modes']) ? $inputs['payment_modes'] : null,
        'users' => isset($inputs['users']) ? $inputs['users'] : null,
        'admins' => isset($inputs['admins']) ? $inputs['admins'] : null,
        'cuisines' => isset($inputs['cuisines']) ? $inputs['cuisines'] : null,
        'categories' => isset($inputs['categories']) ? $inputs['categories'] : null,
        'meals' => isset($inputs['meals']) ? $inputs['meals'] : null,
        'combos' => isset($inputs['combos']) ? $inputs['combos'] : null,
        'areas' => isset($inputs['areas']) ? $inputs['areas'] : null,
        'cities' => isset($inputs['cities']) ? $inputs['cities'] : null,
        'states' => isset($inputs['states']) ? $inputs['states'] : null,
        'discount_amount_value' => isset($inputs['discount_amount_value']) ? $inputs['discount_amount_value'] : null,
        'discount_percent_value' => isset($inputs['discount_percent_value']) ? $inputs['discount_percent_value'] : null,
        'goodies' => isset($inputs['goodies']) ? $inputs['goodies'] : null,
        'cashback_percent_value' => isset($inputs['cashback_percent_value']) ? $inputs['cashback_percent_value'] : null,
        'cashback_value' => isset($inputs['cashback_value']) ? $inputs['cashback_value'] : null,
        'points_value' => isset($inputs['points_value']) ? $inputs['points_value'] : null,
        'give_meals' => isset($inputs['give_meals']) ? $inputs['give_meals'] : null,
        'give_combos' => isset($inputs['give_combos']) ? $inputs['give_combos'] : null,
        'offer_text'  => isset($inputs['offer_text']) ? $inputs['offer_text'] : ''
    ];
  }

  public function newCoupon($inputs)
  {
    $coupon = new Coupon;
    $coupon->code = $inputs['coupon_code'];
    $coupon->description = $inputs['description'];
    $coupon->status = $inputs['status'];
    $coupon->is_offer = $inputs['is_offer'];
    $coupon->offer_text = $inputs['offer_text'];
    $coupon->save();

    //Checks
    $min_quantity = $inputs['min_quantity'];
    if($min_quantity!=null && $min_quantity!='')
    {
      $this->checkCouponAttribute($coupon->id,'total_min_quantity',$min_quantity);
    }

    $one_time = $inputs['one_time'];
    if($one_time!=null)
    {
      $this->checkCouponAttribute($coupon->id,'one_time',$one_time);
    }

    $android = $inputs['android'];
    if($android!=null)
    {
      $this->checkCouponAttribute($coupon->id,'source',config('bueno.source.android'));
    }

    $ios = $inputs['ios'];
    if($ios!=null)
    {
      $this->checkCouponAttribute($coupon->id,'source',config('bueno.source.ios'));
    }

    $web = $inputs['web'];
    if($web!=null)
    {
      $this->checkCouponAttribute($coupon->id,'source',config('bueno.source.web'));
    }

    $web = $inputs['quick'];
    if($web!=null)
    {
      $this->checkCouponAttribute($coupon->id,'source',5);
    }

    $start = $inputs['start'];
    if($start!=null)
    {
      $this->checkCouponAttribute($coupon->id,'from',new DateTime($start));
    }

    $end = $inputs['end'];
    if($end!=null)
    {
      $this->checkCouponAttribute($coupon->id,'to',new DateTime($end));
    }

    $min_order_amount = $inputs['min_order_amount'];
    if($min_order_amount!=null  && $min_order_amount!='')
    {
      $this->checkCouponAttribute($coupon->id,'min_order_amount',$min_order_amount);
    }

    $max_discount_amount = $inputs['max_discount_amount'];
    if($max_discount_amount!=null && $max_discount_amount!='')
    {
      $this->checkCouponAttribute($coupon->id,'max_discount_amount',$max_discount_amount);
    }

    $max_cashback_amount = $inputs['max_cashback_amount'];
    if($max_cashback_amount!=null && $max_cashback_amount!='')
    {
      $this->checkCouponAttribute($coupon->id,'max_cashback_amount',$max_cashback_amount);
    }

    $value_n = $inputs['value_n'];
    if($value_n!=null && $value_n!='')
    {
      $this->checkCouponAttribute($coupon->id,'nth_order',$value_n);
    }

    $kitchen_specific = $inputs['kitchens'];
    if($kitchen_specific!=null)
    {
      foreach ($kitchen_specific as $key => $value) {
        $this->checkCouponAttribute($coupon->id,'on_kitchen',$value);
      }
    }

    $payment_modes = $inputs['payment_modes'];
    if($payment_modes!=null)
    {
      foreach ($payment_modes as $key => $value) {
        $this->checkCouponAttribute($coupon->id,'on_payment_gateway',$value);
      }

    }

    $users = $inputs['users'];
    if($users!=null)
    {
      foreach ($users as $key => $value) {
        $this->checkCouponAttribute($coupon->id,'for_user',$value);
      }

    }

    $byUser = $inputs['admins'];
    if($byUser!=null)
    {
      foreach ($byUser as $key => $value) {
        $this->checkCouponAttribute($coupon->id,'by_user',$value);
      }
    }


    $cuisines = $inputs['cuisines'];

    if($cuisines!=null)
    {
      foreach ($cuisines as $cuisine) {
        $this->checkCouponItem($coupon->id,$cuisine['id'],$cuisine['quantity'],'cuisine');
      }

    }

    $categories = $inputs['categories'];

    if($categories!=null)
    {
      foreach ($categories as $category) {
        $this->checkCouponItem($coupon->id,$category['id'],$category['quantity'],'category');
      }

    }

    $meals = $inputs['meals'];

    if($meals!=null)
    {
      foreach ($meals as $meal) {
        $item = Item::where('itemable_type','App\Models\Meal')->where('itemable_id',$meal['id'])->first();
        $this->checkCouponItem($coupon->id,$item->id,$meal['quantity'],'item');
      }

    }

    $combos = $inputs['combos'];
    if($combos!=null)
    {
      foreach ($combos as $combo) {
        $item = Item::where('itemable_type','App\Models\Combo')->where('itemable_id',$combo['id'])->first();
        $this->checkCouponItem($coupon->id,$item->id,$combo['quantity'],'item');
      }
    }


    $areas = $inputs['areas'];
    if($areas!=null)
    {
      foreach ($areas as $key => $value) {
        $this->checkCouponAttribute($coupon->id,"on_area",$value);
      }
    }

    $cities = $inputs['cities'];
    if($cities!=null)
    {
      foreach ($cities as $key => $value) {
        $this->checkCouponAttribute($coupon->id,"on_city",$value);
      }
    }

    $states = $inputs['states'];
    if($states!=null)
    {
      foreach ($states as $key => $value) {
        $this->checkCouponAttribute($coupon->id,"on_state",$value);
      }
    }

    //Gives

    $discount_amount_value = $inputs['discount_amount_value'];

    if($discount_amount_value!=null)
    {
      $this->giveCouponAttribute($coupon->id,"discount_value",$discount_amount_value);
    }

    $discount_percent_value = $inputs['discount_percent_value'];

    if($discount_percent_value!=null)
    {
      $this->giveCouponAttribute($coupon->id,"discount_percent",$discount_percent_value);
    }

    $goodies = $inputs['goodies'];

    if($goodies!=null)
    {
      foreach ($goodies as $goody) {
        $item = Item::where('itemable_id',$goody['id'])->where('itemable_type','App\Models\Goody')->first();
        $this->giveCouponItem($coupon->id,$item->id,$goody['quantity']);
      }

    }

    $cashback_percent_value = $inputs['cashback_percent_value'];

    if($cashback_percent_value!=null)
    {
      $this->giveCouponAttribute($coupon->id,"cashback_percent_value",$cashback_percent_value);
    }

    $cashback_value = $inputs['cashback_value'];

    if($cashback_value!=null)
    {
      $this->giveCouponAttribute($coupon->id,"cashback_value",$cashback_value);
    }

    $points_value = $inputs['points_value'];

    if($points_value!=null)
    {
      $this->giveCouponAttribute($coupon->id,"points_value",$points_value);
    }

    $meals = $inputs['give_meals'];

    if($meals!=null)
    {
      foreach ($meals as $meal) {
        $item = Item::where('itemable_id',$meal['id'])->where('itemable_type','App\Models\Meal')->first();
        $this->giveCouponItem($coupon->id,$item->id,$meal['quantity']);
      }
    }

    $combos = $inputs['give_combos'];

    if($combos!=null)
    {
      foreach ($combos as $combo) {
        $item = Item::where('itemable_id',$combo['id'])->where('itemable_type','App\Models\Combo')->first();
        $this->giveCouponItem($coupon->id,$item->id,$combo['quantity']);
      }
    }
  }

  public function giveCouponItem($coupon_id,$item_id,$quantity)
  {
    $giveaway = $this->giveCouponAttribute($coupon_id,'item',$item_id);
    $giveaway_quantity = new CouponGiveaway;
    $giveaway_quantity->coupon_id = $coupon_id;
    $giveaway_quantity->giveaway = 'quantity';
    $giveaway_quantity->value = $quantity;
    $giveaway_quantity->group = $giveaway->id;
    $giveaway_quantity->save();
  }

  public function giveCouponAttribute($coupon_id,$attribute,$value)
  {
    $giveaway = new CouponGiveaway;
    $giveaway->coupon_id = $coupon_id;
    $giveaway->giveaway = $attribute;
    $giveaway->value = $value;
    $giveaway->save();
    $giveaway->group = $giveaway->id;
    $giveaway->save();
    return $giveaway;
  }

  public function checkCouponItem($coupon_id,$item_id,$quantity,$check_name)
  {
    $check = $this->checkCouponAttribute($coupon_id,$check_name,$item_id);
    $check_quantity = new CheckCoupon;
    $check_quantity->coupon_id = $coupon_id;
    $check_quantity->check = 'quantity';
    $check_quantity->value = $quantity;
    $check_quantity->group = $check->id;
    $check_quantity->save();
    return $check;

  }

  public function checkCouponAttribute($coupon_id,$attribute,$value)
  {
    $check = new CheckCoupon;
    $check->coupon_id = $coupon_id;
    $check->check = $attribute;
    $check->value = $value;
    $check->save();
    $check->group = $check->id;
    $check->save();
    return $check;
  }

  public function deleteCheckAttribute($attribute,$coupon)
  {
    $check = $coupon->checks->where('check',$attribute)->first();
    return $check->delete();
  }

  public function deleteMultipleCheckAttribute($attribute,$coupon)
  {
    $checks = CheckCoupon::where('coupon_id',$coupon->id)->where('check',$attribute)->delete();
    return true;
  }

  public function deleteMultipleGroupCheckAttribute($attribute,$coupon)
  {
    $groups = CheckCoupon::where('coupon_id',$coupon->id)->where('check',$attribute)->get()->pluck('group')->toArray();
    CheckCoupon::whereIn('group',$groups)->delete();
    return true;
  }

  public function deleteMultipleGroupGiveAttribute($attribute,$coupon)
  {
    $groups = CouponGiveaway::where('coupon_id',$coupon->id)->where('giveaway',$attribute)->get()->pluck('group')->toArray();
    CouponGiveaway::whereIn('group',$groups)->delete();
    return true;
  }

  public function deleteCheckSourceAttribute($source_id,$coupon)
  {
    $check = $coupon->checks->where('check','source')->where('value',''.$source_id)->first();
    if($check)
      $check->delete();
  }

  public function createCheckSourceAttribute($source_id,$coupon)
  {
    return $this->checkCouponAttribute($coupon->id,'source',$source_id);
  }

  public function updateCheckAttribute($attribute,$value,$coupon)
  {
    $attribute = $coupon->checks->where('check',$attribute)->first();
    $attribute->value = $value;
    $attribute->save();
  }

  public function updateGiveAttribute($attribute,$value,$coupon)
  {
    $attribute = $coupon->gives->where('giveaway',$attribute)->first();
    $attribute->value = $value;
    $attribute->save();
  }

  public function updateMinOrderAmount($amount,$coupon)
  {
    $min_order_amount = $coupon->checks->where('check','min_order_amount')->first();
    $min_order_amount->value = $amount ;
    $min_order_amount->save();
  }

  public function updateCouponFromDate($start,$coupon)
  {
    $date = new DateTime($start);
    $coupon_start_date = $coupon->checks->where('check','from')->first();
    $coupon_start_date->value = $date;
    $coupon_start_date->save();
  }

  public function updateCouponToDate($to,$coupon)
  {
    $date = new DateTime($to);
    $coupon_start_date = $coupon->checks->where('check','to')->first();
    $coupon_start_date->value = $date;
    $coupon_start_date->save();
  }

  public function createCouponFromDate($start,$coupon)
  {
    $date = new DateTime($start);
    $coupon_start_date = new CheckCoupon;
    $coupon_start_date->check = 'from';
    $coupon_start_date->coupon_id = $coupon->id;
    $coupon_start_date->value = $date;
    $coupon_start_date->save();
  }

  public function createCouponToDate($to,$coupon)
  {
    $date = new DateTime($to);
    $coupon_start_date = new CheckCoupon;
    $coupon_start_date->check = 'to';
    $coupon_start_date->coupon_id = $coupon->id;
    $coupon_start_date->value = $date;
    $coupon_start_date->save();
  }



  public function deleteGiveAttribute($attribute,$coupon)
  {
    $check = $coupon->gives->where('giveaway',$attribute)->first();
    return $check->delete();
  }

  public function getCouponAttributes($coupon)
  {
    $checks = $coupon->checks;
    $gives = $coupon->gives;

    //Checks

    // Min Order Amount and Quantity

    $min_order_amount = $checks->where('check','min_order_amount')->first();
    if($min_order_amount)
    $min_order_amount = $min_order_amount->value;
    else
    $min_order_amount =null;

    $max_discount_amount = $checks->where('check','max_discount_amount')->first();
    if($max_discount_amount)
      $max_discount_amount = $max_discount_amount->value;
    else
      $max_discount_amount =null;

    $max_cashback_amount= $checks->where('check','max_cashback_amount')->first();
    if($max_cashback_amount)
      $max_cashback_amount = $max_cashback_amount->value;
    else
      $max_cashback_amount =null;

    $min_quantity = $checks->where('check','total_min_quantity')->first();
    if($min_quantity)
      $min_quantity = $min_quantity->value;
    else
      $min_quantity =null;

    // Kitchens

    $kitchens = $checks->where('check','on_kitchen')->pluck('value')->toArray();
    if($kitchens==null)
      $kitchens = ['0'];

    // Payment Modes

    $payment_modes = $checks->where('check','on_payment_gateway')->pluck('value')->toArray();
    if($payment_modes==null)
      $payment_modes = ['0'];

    // Areas Cities and States

    $areas = $checks->where('check','on_area')->pluck('value')->toArray();
    if($areas==null)
      $areas = ['0'];

    $cities = $checks->where('check','on_city')->pluck('value')->toArray();
    if($cities==null)
      $cities = ['0'];

    $states = $checks->where('check','on_state')->pluck('value')->toArray();
    if($states==null)
      $states = ['0'];

    //Users and Admins

    $users = $checks->where('check','for_user')->pluck('value')->toArray();
    $coupon_user = null;
    $counter = 0 ;
    if(count($users)!=0)
    {
      foreach($users as $key => $value)
      {
        $Auth_user = User::find($value);
        $coupon_user[$counter]['name'] = $Auth_user->phone.' | '.$Auth_user->full_name;
        $coupon_user[$counter]['id'] = $Auth_user->id;
        $counter++;
      }
    }

    $admins = $checks->where('check','by_user')->pluck('value')->toArray();
    $coupon_admin = null;
    $counter = 0 ;
    if(count($admins)!=0)
    {
      foreach($admins as $key => $value)
      {
        $Auth_user = User::find($value);
        $coupon_admin[$counter]['name'] = $Auth_user->phone.' | '.$Auth_user->full_name;
        $coupon_admin[$counter]['id'] = $Auth_user->id;
        $counter++;
      }
    }

    // Nth Order

    $nth_order = $checks->where('check','nth_order')->first();
    if($nth_order)
      $nth_order = $nth_order->value;
    else
      $nth_order =null;

    // Cuisines and Categories

    $cuisines = $checks->where('check','cuisine')->pluck('value')->toArray();
    if($cuisines==null)
      $on_cuisines = null;

    $cuisines = $checks->where('check','cuisine');
    if($cuisines->count()==0)
      $on_cuisines = null;
    else
    {
      foreach($cuisines as $cuisine)
      {
        $quantity = CheckCoupon::where('group',$cuisine->group)->where('check','quantity')->first()->value;
        $on_cuisines[] = [
          'id' => $cuisine->value,
          'quantity' => $quantity
        ];
      }
    }

    $categories = $checks->where('check','category');
    if($categories->count()==0)
      $on_categories = null;
    else
    {
      foreach($categories as $category)
      {
        $quantity = CheckCoupon::where('group',$category->group)->where('check','quantity')->first()->value;
        $on_categories[] = [
            'id' => $category->value,
            'quantity' => $quantity
        ];
      }
    }

    $items = $checks->where('check','item');
    if($items->count()==0)
      $on_items = null;
    else
    {
      foreach($items as $item)
      {
        $quantity = CheckCoupon::where('group',$item->group)->where('check','quantity')->first()->value;
        $on_items[] = [
            'id' => $item->value,
            'quantity' => $quantity
        ];
      }
    }
    $coupon_from = $checks->where('check','from')->first();
    if($coupon_from)
    {
      $coupon_from = $coupon_from->value;
      $start = new DateTime($coupon_from);
      $start = $start->format('Y/m/d H:m:s');
    }
    else
    {
      $coupon_from = null;
      $start = null;
    }

    $coupon_to = $checks->where('check','to')->first();
    if($coupon_to)
    {
      $coupon_to = $coupon_to->value;
      $end = new DateTime($coupon_to);
      $end = $end->format('Y/m/d H:m:s');
    }
    else
    {
      $coupon_to = null;
      $end = null;
    }

    $one_time = $checks->where('check','one_time')->first();
    if($one_time)
      $one_time = 1;
    else
      $one_time =null;

    $web = $checks->where('check','source')->where('value','7')->first();
    if($web)
      $web = 1;
    else
      $web =null;

    $ios = $checks->where('check','source')->where('value','4')->first();
    if($ios)
      $ios = 1;
    else
      $ios =null;

    $android = $checks->where('check','source')->where('value','1')->first();
    if($android)
      $android = 1;
    else
      $android = null;

    $quick = $checks->where('check','source')->where('value','5')->first();
    if($quick)
      $quick = 1;
    else
      $quick = null;

    //Gives

    // Discount Amount and Percent

    $discount_value = $gives->where('giveaway','discount_value')->first();
    if($discount_value)
      $discount_value = $discount_value->value;
    else
      $discount_value =null;

    $discount_percent = $gives->where('giveaway','discount_percent')->first();
    if($discount_percent)
      $discount_percent = $discount_percent->value;
    else
      $discount_percent =null;

    $give_items = $gives->where('giveaway','item');
    if($give_items->count()==0)
      $give_Items = null;
    else
    {
      foreach($give_items as $item)
      {
        $quantity = CouponGiveaway::where('group',$item->group)->where('giveaway','quantity')->first()->value;
        $give_Items[] = [
            'id' => $item->value,
            'quantity' => $quantity
        ];
      }
    }
    $points_value = $gives->where('giveaway','points_value')->first();
    if($points_value)
      $points_value = $points_value->value;
    else
      $points_value =null;

    $cashback_percent_value = $gives->where('giveaway','cashback_percent_value')->first();
    if($cashback_percent_value)
      $cashback_percent_value = $cashback_percent_value->value;
    else
      $cashback_percent_value =null;


    return [
      'min_order_amount' => $min_order_amount,
      'max_discount_amount' => $max_discount_amount,
      'max_cashback_amount' => $max_cashback_amount,
      'min_quantity' => $min_quantity,
      'on_kitchens' => $kitchens,
      'payment_modes' => $payment_modes,
      'areas' => $areas,
      'cities' => $cities,
      'states' => $states,
      'users' => $coupon_user,
      'admins' => $coupon_admin,
      'nth_order' => $nth_order,
      'cuisines' =>$on_cuisines,
      'categories' => $on_categories,
      'items' => $on_items,
      'from' => $start,
      'to' => $end,
      'discount_percent' => $discount_percent,
      'discount_value' =>$discount_value,
      'give_items' => $give_Items,
      'points_value' => $points_value,
      'cashback_percent_value' => $cashback_percent_value,
      'one_time' => $one_time,
      'web' => $web,
      'ios'=> $ios,
      'android' => $android,
      'quick' => $quick
    ];
  }

  public function deleteCouponFromDate($coupon)
  {
    $check = $coupon->checks->where('check','from')->first();
    if($check)
    $check->delete();
  }

  public function deleteCouponToDate($coupon)
  {
    $check = $coupon->checks->where('check','to')->first();
    if($check)
    $check->delete();
  }

  public function getCouponOffers()
  {
    return Coupon::where('is_offer', 1)->where('status', 1)->get();
  }

}
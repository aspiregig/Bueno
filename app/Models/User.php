<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends BaseModel implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{

  use Authenticatable, Authorizable, CanResetPassword;

  protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'group_id', 'date_of_birth', 'confirmation_code', 'gender', 'mobile_otp', 'group_id', 'membership_id', 'referral_code', 'mobile_verify', 'last_login_at','status', 'otp_created_at','sms_notify','email_notify' , 'password', 'points'];

  protected $appends = ['full_name','total_money_spent','total_orders','total_refferals','first_order','last_order'];

  protected $hidden = ['password', 'remember_token'];

  protected $dates = ['otp_created_at', 'last_login_at'];

  protected $repo = 'Bueno\Repositories\DbUserRepository';

  public function group()
  {
    return $this->belongsTo('App\Models\Group');
  }

  public function orders()
  {
    return $this->hasMany('App\Models\Order');
  }

  public function confirmedOrders()
  {
    $orders = $this->orders;

    $orders = $orders->filter(function($order){
      if($order->paymentInfo && in_array($order->source_id, array_values(config('bueno.source'))))
      return $order->paymentInfo->status== 3 && $order->status == 4  && $order->created_at >= config('bueno.site.live_date')? true : false;
      else
        return false;
    });

    return $orders;
  }

  public function referralCoupon()
  {
    return $this->hasOne('App\Models\Coupon','code','referral_code');
  }

  public function nonCancelledOrders()
  {
    $orders = $this->orders;

    $orders = $orders->filter(function($order){
      if($order->paymentInfo && in_array($order->source_id, array_values(config('bueno.source'))))
      return $order->status!=6 ? true : false;
      else
        return false;
    });

    return $orders;
  }

  public function getFirstOrderAttribute()
  {
    return $this->orders->first();
  }

  public function getLastOrderAttribute()
  {
    return $this->orders->last();
  }

  public function getFullNameAttribute()
  {
    return $this->first_name." ".$this->last_name;
  }

  public function getTotalOrdersAttribute()
  {
    return $this->orders->count();
  }

  public function getTotalMoneySpentAttribute()
  {
    $sum = 0;
      $this->orders->map(function($order) use (&$sum){
        if($order->paymentInfo)
      if($order->paymentInfo->status==3)
        $sum += $order->paymentInfo->amount;
    });

    return $sum;
  }

  public function getTotalRefferalsAttribute()
  { 
    return $this->hasMany('App\Models\User','referrer_id')->count();
  }
  public function addresses()
  {
    return $this->hasMany('App\Models\Address','user_id');
  }

  public function defaultAdderss()
  {
    return $this->addresses->where('is_default', 1)->first();
  }

  public function cartItems()
  {
    return $this->hasMany('App\Models\Cart')->with('item.itemable');
  }

  public function manageKitchen()
  {
    return $this->hasOne('App\Models\KitchenManager','user_id');
  }

  public function kitchen()
  {
    return $this->belongstoMany('App\Models\Kitchen','kitchen_managers');
  }

  public function otp()
  {
    return $this->hasOne('App\Models\Otp');
  }

  public function membership()
  {
    return $this->belongsTo('App\Models\Membership');
  }

  public function saved_items()
  {
    return $this->hasMany('App\Models\Favorite');
  }

  public function area_addresses()
  {
    $area_id = session('area_id') ? (Integer) session('area_id'): null;

    if(!$area_id) return [];
    return $this->addresses->where('area_id', $area_id);

  }

  public function referrer()
  {
    return $this->belongsTo('App\Models\User','referrer_id');
  }

}
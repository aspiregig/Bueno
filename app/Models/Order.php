<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model{

  protected $fillable = [
      'name',
      'address_id',
      'user_id',
      'source_id',
      'payment_mode_id',
      'status',
      'status',
      'coupon_id',
      'delivery_address',
      'delivery_area_id',
      'instruction',
      'company_name',
      'kitchen_id',
      'redeem_points',
      'ngo_id',
      'donation_amount',
      'user_phone',
      'is_printed',
      'created_at',
      'longitude',
      'latitude',
      'resource_order_no',
      'feedback_on_missed_call',
      'feedback_missed_call_time'
  ];

  public function statusText()
  {
    return $this->belongsTo('App\Models\OrderStatus','status');
  }

  public function paymentInfo()
  {
    return $this->hasOne('App\Models\Payment');
  }

  public function paymentMode()
  {
    return $this->belongsTo('App\Models\PaymentMode');
  }

  public function user()
  {
    return $this->belongsTo('App\Models\User');
  }

  public function source()
  {
    return $this->belongsTo('App\Models\OrderSource');
  }

  public function ngo()
  {
    return $this->belongsTo('App\Models\Ngo');
  }
  
  public function address()
  {
    return $this->belongsTo('App\Models\Address');
  }

  public function meals()
  {
    return  $this->belongsToMany('App\Models\Item')->withPivot('quantity', 'unit_price')->where('itemable_type','App\Models\Meal');
  }

  public function combos()
  {
    return  $this->belongsToMany('App\Models\Item')->withPivot('quantity', 'unit_price')->where('itemable_type','App\Models\Combo');
  }

  public function goodies()
  {
    return  $this->belongsToMany('App\Models\Item')->withPivot('quantity')->where('itemable_type','App\Models\Goody');
  }

  public function coupon()
  {
    return  $this->belongsTo('App\Models\Coupon');
  }

  public function kitchen()
  {
    return $this->belongsTo('App\Models\Kitchen');
  }

  public function items()
  {
    return $this->belongsToMany('App\Models\Item')->withPivot(['quantity','unit_price']);
  }

  public function itemsInfo()
  {
    return $this->hasMany('App\Models\ItemOrder');
  }

  public function orderItems()
  {
    return $this->belongsToMany('App\Models\Item')->withPivot('quantity', 'unit_price');
  }

  public function deliveryBoy()
  {
    return $this->belongsTo('App\Models\DeliveryBoy','delivery_boy_id');
  }

  public function area()
  {
    return $this->belongsTo('App\Models\Area','delivery_area_id');
  }

  public function invoice()
  {
    return $this->hasMany('App\Models\OrderInvoice');
  }

  public function joolehLog()
  {
    return $this->hasOne('App\Models\JoolehLog');
  }

  public function logs()
  {
    return $this->hasMany('App\Models\LogOrder');
  }

  public function cancelReason()
  {
    return $this->belongsTo('App\Models\CancelReason');
  }

}

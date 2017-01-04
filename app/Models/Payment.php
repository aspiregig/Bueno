<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model{

  protected $fillable = ['order_id','status','amount','transaction_id','payment_mode_id', 'comment', 'cashback_buff', 'settlement_amount', 'transaction_details'];
  
  public function  order()
  {
    return $this->belongsTo('App\Models\Order');
  }

  public function paymentStatus()
  {
    return $this->belongsTo('App\Models\PaymentStatus','status');
  }

  public function paymentMode()
  {
    return $this->belongsTo('App\Models\PaymentMode','payment_mode_id');
  }
}

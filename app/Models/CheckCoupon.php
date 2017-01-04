<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckCoupon extends Model{

  protected $table = 'check_coupon';

  protected $fillable = ['coupon_id', 'check', 'value', 'group'];
  
  
}
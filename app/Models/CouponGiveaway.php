<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponGiveaway extends Model{

  protected $table = 'coupon_giveaway';

  protected $fillable = ['coupon_id', 'giveaway', 'value', 'group'];
  
  
}
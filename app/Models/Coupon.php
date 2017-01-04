<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends BaseModel{

  protected $fillable = ['code','description', 'status','type'];

  protected $repo = 'Bueno\Repositories\DbCouponRepository';

  public function checks()
  {
    return $this->hasMany('App\Models\CheckCoupon');
  }

  public function gives()
  {
    return $this->hasMany('App\Models\CouponGiveaway');
  }
}
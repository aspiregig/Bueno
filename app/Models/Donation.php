<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model{

  protected $table = 'donation_order';

  public function orders()
  {
    return $this->hasMany('App\Model\Order');
  }
  public function ngo()
  {
    return $this->belongsTo('App\Models\Ngo');
  }

}
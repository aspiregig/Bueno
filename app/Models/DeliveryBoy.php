<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryBoy extends Model{

  protected $fillable = ['full_name','kitchen_id','phone','vehicle_name','vehicle_number','jooleh_pass','jooleh_uid'];
  

  public function kitchen()
  {
    return $this->belongsTo('App\Models\Kitchen');
  }

  public function orders()
  {
    return $this->hasMany('App\Models\Order','delivery_boy_id');
  }
  
}

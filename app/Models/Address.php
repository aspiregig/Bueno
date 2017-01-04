<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model{

  protected $fillable = ['id','address_name','state_id','city_id','area_id','address','user_id', 'pincode', 'is_default'];
  protected $table = 'address_user';
  
  public function user()
  {
    $this->hasOne('App/Models/User');
  }

  public function area()
  {
    return $this->belongsTo('App\Models\Area');
  }
  public function city()
  {
    return $this->belongsTo('App\Models\City');
  }
  public function state()
  {
    return $this->belongsTo('App\Models\State');
  }
  
}
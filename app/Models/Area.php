<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends BaseModel{

  protected $fillable = ['city_id','name','status','min_order_amount','delivery_time','pincode','longitude','latitude'];

    protected $repo = 'Bueno\Repositories\DbLocationRepository';

    public function city()
    {
      return $this->belongsTo('App\Models\City');
    }

    public function state()
    {
      return $this->city->state;
    }

    public function addresses()
    {
      return $this->addresses('App\Models\Address');
    }

    public function kitchen()
    {
      return $this->belongsToMany('App\Models\Kitchen');
    }

    public function firstKitchen()
    {
      return $this->belongsToMany('App\Models\Kitchen')->first();
    }

}
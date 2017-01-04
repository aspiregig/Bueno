<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kitchen extends Model{

  protected $fillable = ['name','status','delivery_charge','packaging_charge','vat','service_tax','service_charge','jooleh_username','jooleh_token'];

    public function cities()
    {
      return $this->hasMany('App\Models\City');
    }

    public function areas()
    {
      return $this->belongsToMany('App\Models\Area');
    }

    public function managers()
    {
      return $this->belongsToMany('App\Models\User','kitchen_managers');
    }

    public function orders()
    {
      return $this->hasMany('App\Models\Order');
    }

    public function delivery_boys()
    {
      return $this->hasMany('App\Models\DeliveryBoy');
    }

    public function kitchenItems()
    {
      $items = $this->belongsToMany('App\Models\Item','stocks')->withPivot('value')->with('itemable');

      return $items;
    }

}
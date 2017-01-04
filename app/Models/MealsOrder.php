<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealsOrder extends Model{

  protected $fillable = ['order_id','meal_id','meal_quantity'];

  protected $table = 'meals_order';

  public function cuisine()
  {
    return $this->hasOne('App\Models\Cuisine');
  }

  public function availabilities()
  {
    return $this->belongsToMany('App\Models\Availability');
  }
}
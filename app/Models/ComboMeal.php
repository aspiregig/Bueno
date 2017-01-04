<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComboMeal extends Model{

  protected $fillable = ['combo_id','meal_id','quantity'];

  protected $table = 'combo_meal';

  public function meal()
  {
    return $this->belongsTo('App\Models\Meal');
  }

  public function combo()
  {
    return $this->belongsTo('App\Models\Combo');
  }

}
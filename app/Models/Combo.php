<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Combo extends Model{

  protected $fillable = ['name','slug','serves','description','weight','original_price','discount_price','status','is_xprs','is_hot_deal','is_sellable','long_description','type','spice_level','cuisine_id','category_id'];

  public function meals()
  {
    return $this->hasMany('App\Models\ComboMeal');
  }

  public function comboMeals()
  {
    return $this->belongsToMany('App\Models\Meal','combo_meal');
  }

  public function cuisine()
  {
    return $this->belongsTo('App\Models\Cuisine');
  }

  public function category()
  {
    return $this->belongsTo('App\Models\Category','category_id');
  }

  public function spiceLevel()
  {
    return $this->belongsTo('App\Models\MealSpiceLevel','spice_level');
  }

  public function foodType()
  {
    return $this->belongsTo('App\Models\MealFoodType','type');
  }

  public function getShortDescriptionAttribute()
  {
    $length = strlen($this->description);

    $description = $length > 120 ? substr($this->description, 0, 120) . '...' : $this->description;

    return $description;
  }

}
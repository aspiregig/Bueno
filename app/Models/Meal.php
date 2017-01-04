<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model{

  protected $fillable = ['name','slug','serves','type','spice_level','description','cuisine_id','category_id','display_pic_url','weight','original_price','discount_price','status','is_xprs','is_hot_deal','is_sellable','long_description'];

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

  public function combos()
  {
    return $this->belongsToMany('App\Models\Combo','combo_meal','meal_id','combo_id');
  }

  public function getShortDescriptionAttribute()
  {
    $length = strlen($this->description);

    $description = $length > 120 ? substr($this->description, 0, 120) . '...' : $this->description;

    return $description;
  }
  
}
<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealFoodType extends Model{

  protected $fillable = ['name', 'status'];

  protected $table = 'meal_type';

}
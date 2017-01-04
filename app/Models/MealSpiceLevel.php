<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealSpiceLevel extends Model{

  protected $fillable = ['name', 'status'];

  protected $table = 'meal_spice_level';

}
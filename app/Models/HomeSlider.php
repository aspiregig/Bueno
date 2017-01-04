<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSlider extends Model{

  protected $fillable = ['name','status','description','link_url','sequence'];

  protected $table = 'home_sliders';

}
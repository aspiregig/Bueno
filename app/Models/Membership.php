<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model{

  protected $fillable = ['name', 'bg_color', 'text_color', 'min','loyalty_points','description'];

  public function members()
  {
    return $this->hasMany('App\Models\User');
  }

}
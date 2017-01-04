<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model{

  protected $fillable = ['name','state_id','status'];
  
  public function areas()
  {
    return $this->hasMany('App\Models\Area','city_id');
  }

  public function state()
  {
    return $this->belongsTo('App\Models\State');
  }

  public function addresses()
  {
    return $this->hasMany('App\Models\Address');
  }
}
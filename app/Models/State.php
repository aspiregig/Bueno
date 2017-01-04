<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model{

  protected $fillable = ['name','status'];
  
  public function cities()
  {
    return $this->hasMany('App\Models\City','state_id');
  }

}
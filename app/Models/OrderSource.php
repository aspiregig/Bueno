<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSource extends Model{

  public function orders()
  {
    return $this->hasMany('App\Models\Order','source_id');
  }

 }
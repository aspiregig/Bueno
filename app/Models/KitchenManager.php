<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KitchenManager extends Model{

  protected $table = 'kitchen_managers';

  public function user()
  {
    $this->belongsTo('App/Models/User','user_id');
  }

}
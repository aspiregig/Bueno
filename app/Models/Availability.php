<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model{

  protected $fillable = ['name','status','sequence'];

  public $timestamps = false;
  
  public function items()
  {
    return $this->belongsToMany('App\Models\Item');
  }

}

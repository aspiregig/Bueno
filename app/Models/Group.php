<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model{

  protected $fillable = ['name'];

  protected $append = ['total_associated_user'];

  public function role()
  {
    return $this->hasOne('App\Models\Role');
  }

  public function getTotalAssociatedUserAttribute()
  {
    return $this->users->count();
  }

  public function users()
  {
    return $this->hasMany('App\Models\User');
  }

}
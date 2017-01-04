<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model{

  protected $fillable = ['group_id','status'];

  public function groups()
  {
    return $this->hasMany('App\Models\Group');
  }

  public function role_permissions()
  {
    return $this->hasMany('App\Models\RolePermission');
  }

  public function permissions()
  {
    return $this->belongsToMany('App\Models\Permission', 'role_permissions');
  }

}
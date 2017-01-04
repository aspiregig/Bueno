<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
  public static function repo()
  {
    $instance = new static;

    return new $instance->repo($instance);
  }
}
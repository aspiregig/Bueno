<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timing extends Model{

  /**
   * @var array
   */
  protected $fillable = ['from', 'to'];

  /**
   * @param $value
   * @return mixed
   */
  public function getFromAttribute($value)
  {
    return substr_replace(sprintf('%04d', $value), ':', 2, 0);
  }

  /**
   * @param $value
   * @return mixed
   */
  public function getToAttribute($value)
  {
    return substr_replace(sprintf('%04d', $value), ':', 2, 0);
  }
  
}

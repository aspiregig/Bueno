<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model{

  protected $fillable = ['item_id', 'user_id'];

  public $timestamps = false;

  public function item()
  {
    return $this->belongsTo('App\Models\Item');
  }

  public function user()
  {
    return $this->belongsTo('App\Models\User');
  }
}
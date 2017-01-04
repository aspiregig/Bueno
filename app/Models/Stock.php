<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model{

  protected $fillable = ['kitchen_id', 'item_id', 'value'];

  public function kitchen()
  {
    return $this->belongsTo('App\Models\Kitchen');
  }

  public function item()
  {
    return $this->belongsTo('App\Models\Item');
  }

}
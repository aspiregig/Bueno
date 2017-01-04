<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOrder extends Model{

  protected $fillable = ['order_id','item_id','quantity', 'unit_price'];

  protected $table = 'item_order';

  public function order()
  {
    return $this->belongsTo('App\Models\Order');
  }

}
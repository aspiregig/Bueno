<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogOrder extends Model{

  protected $fillable = ['order_id','user_id','comment','status_from_id','status_to_id'];
  
  protected $table = 'log_order';

  public function user()
  {
    return $this->belongsTo('App\Models\User');
  }

  public function order()
  {
    return $this->belongsTo('App\Models\Order');
  }

  public function statusFrom()
  {
    return $this->belongsTo('App\Models\OrderStatus','status_from_id');
  }

  public function statusTo()
  {
    return $this->belongsTo('App\Models\OrderStatus','status_to_id');
  }

}

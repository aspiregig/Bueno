<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JoolehLog extends Model
{
  protected $table = 'jooleh_log';

  public function order()
  {
    return $this->belongsTo('App\Models\Order');
  }
}

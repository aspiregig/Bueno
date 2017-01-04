<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CancelReason extends Model{

  protected $table = 'cancel_reasons';
  protected $fillable = ['reason'];
}

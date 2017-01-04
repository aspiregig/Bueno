<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CombosOrder extends Model{

  protected $fillable = ['order_id','combo_id','quantity'];

  protected $table = 'combos_order';

}
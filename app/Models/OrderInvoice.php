<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderInvoice extends Model{

  protected $table = 'order_invoice';

  protected $fillable = ['order_id', 'quantity', 'charge_for', 'amount'];
  
}
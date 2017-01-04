<?php namespace App\Models;

class PaymentMode extends BaseModel{

  protected $fillable = ['status'];

  protected $repo = 'Bueno\Repositories\DbCommonRepository';

}

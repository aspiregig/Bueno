<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessQuery extends Model{

  protected $fillable = ['name','phone','email','query'];
}
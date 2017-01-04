<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Header extends Model{

  protected $fillable = ['content','status','link_url'];
  
}
<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Press extends Model{

  protected $table = 'press';

  protected $fillable = ['image_url', 'title', 'url', 'source_name', 'date'];

}
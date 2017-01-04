<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdText extends Model{

  protected $fillable = ['name','html_content','status'];

  protected $table = 'ad_texts';
  
}
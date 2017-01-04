<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model{

  protected $fillable = ['full_name','content','status','special_text'];

  public function user()
  {
    return $this->belongsTo('App\Models\User');
  }
  
}
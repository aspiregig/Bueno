<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryBanner extends Model{

  protected $fillable = ['image_url','category_id','alt_text','status'];
  
  public function category()
  {
    return $this->belongsTo('App\Models\Category');
  }
}
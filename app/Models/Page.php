<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model{

  protected $fillable = ['slug', 'meta_title', 'meta_description'];

  protected  $appends = ['image_url'];

  public function getImageUrlAttribute()
  {
  	if($this->meta_image_url)
  	{
  		return route('photo.web_page', $this->meta_image_url);
  	}

  	return url() . '/images/logo.png';
  }
  
  
}
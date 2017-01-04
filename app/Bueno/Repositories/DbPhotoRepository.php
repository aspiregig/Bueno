<?php namespace Bueno\Repositories;

use App\Models\Meal;
use App\Models\Ngo;
use App\Models\Page;
use App\Models\Goody;
use App\Models\User;
use App\Models\Combo;
use App\Models\Header;
use App\Models\HomeSlider;


class DbPhotoRepository 
{
  public function getMealPhotoMimeType($filename)
  {
    $meal = Meal::where('display_pic_url', $filename)->orWhere('thumbnail_pic_url',$filename)->first();
    if($meal)
    $mime_type = $meal->display_pic_mime;
    else
      $mime_type = 'image\jpeg';
    return $mime_type;
  }
  public function getPagePhotoMimeType($filename)
  {
    return Page::where('meta_image_url', $filename)->first()->meta_image_mime;
  }
  public function getSliderPhotoMimeType($filename)
  {
    return HomeSlider::where('image_url', $filename)->first()->image_mime_type;
  }
  public function getUserPhotoMimeType($filename)
  {
    $user = User::where('avatar_url', $filename)->first();
    if($user)
    $mime_type = $user->avatar_mime_type;
    else
      $mime_type = 'image\jpeg';
    return $mime_type;
  }
  public function getGoodyPhotoMimeType($filename)
  {
    $goody = Goody::where('image_url', $filename)->first();
    if($goody)
    $mime_type = $goody->display_pic_mime;
    else
    $mime_type = 'image\jpeg';
    return $mime_type;
  }
  public function getComboPhotoMimeType($filename)
  {
    $combo = Combo::where('display_pic_url', $filename)->orWhere('thumbnail_pic_url',$filename)->first();
    if($combo)
      $mime_type = $combo->display_pic_mime;
    else
      $mime_type = 'image\jpeg';
    return $mime_type;
  }
  public function getBannerPhotoMimeType($filename)
  {
    return Header::where('display_image_url', $filename)->first()->display_pic_mime;
  }

  public function getNgoPhotoMimeType($filename)
  {
    return Ngo::where('display_image_url', $filename)->first()->display_pic_mime;
  }

}
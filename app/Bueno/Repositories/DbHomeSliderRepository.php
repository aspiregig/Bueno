<?php namespace Bueno\Repositories;

use App\Models\HomeSlider;

class DbHomeSliderRepository  {

  public function getAllHomeSliders()
  {
    return HomeSlider::orderBy('sequence')->get();
  }

  public function getSliderById($id)
  {
    return HomeSlider::find($id);
  }

  public function updateSlider($id,$inputs)
  {
    $home_slider = $this->getSliderById($id);

    $new_image_check = $inputs->file('image_url');

    if(isset($new_image_check))
    {
    $file = $inputs->file('image_url');
    
    $filename = upload_file($file, 'slider');

    $home_slider->image_url = $filename;

    $home_slider->image_mime_type = $file->getClientMimeType();
    }

    $home_slider->fill($inputs->all())->save();

    return $home_slider;
  }


  public function newHomeSlider($inputs)
  {
    $home_slider = HomeSlider::create($inputs->all());

    $new_image_check = $inputs->file('image_url');
    

    if(isset($new_image_check))
    {
    $file = $inputs->file('image_url');
    
    $filename = upload_file($file, 'slider');

    $home_slider->image_url = $filename;

    $home_slider->image_mime_type = $file->getClientMimeType();
    }
    
    $home_slider->save();

    return $home_slider;
  }

  public function deleteSlider($id)
  { 

    $slider = HomeSlider::find($id);
    if($slider->status==0)
    {
      $slider->delete();
      return true;
    }
    else
    {
    return false;
    }
  }

}
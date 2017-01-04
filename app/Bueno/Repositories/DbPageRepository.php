<?php namespace Bueno\Repositories;

use App\Models\Page;

class DbPageRepository  {

  public function getAllPages()
  {
    return Page::get();
  }

  public function getPageById($id)
  {
    return Page::find($id);
  }

  public function updatePage($id,$inputs)
  {
    $web_page = Page::findOrFail($id);

    $new_image_check = $inputs->file('meta_image_url');

    if(isset($new_image_check))
    {
    $file = $inputs->file('meta_image_url');
    
    $filename = upload_file($file, 'webpages');

    $web_page->meta_image_url = $filename;

    $web_page->meta_image_mime = $file->getClientMimeType();
    }

    return $web_page->fill($inputs->all())->save();
  }

  public function totalOrdersByPageId($id)
  {
    $page = Page::findOrFail($id);
    return $page->orders->count();
  }

  public function newPage($inputs)
  {
    $web_page = Page::create($inputs->all());

    $new_image_check = $inputs->file('meta_image_url');

    if(isset($new_image_check))
    {
    $file = $inputs->file('meta_image_url');
    
    $filename = upload_file($file, 'webpages');

    $web_page->meta_image_url = $filename;

    $web_page->meta_image_mime = $file->getClientMimeType();
  }
  else
    {
      $web_page->meta_image_url = 'bueno';

      $web_page->meta_image_mime = 'image/png';
    }

    $web_page->save();

    return $web_page;

  }

}
<?php namespace Bueno\Repositories;

use App\Models\Ngo;
use App\Models\Order;

class DbNgoRepository  {

  public function getAllNgos()
  {
    return Ngo::get();
  }

  public function newNgo($inputs)
  {
    $ngo =  Ngo::create($inputs->all());

    $new_image_check = $inputs->file('display_image_url');

    if(isset($new_image_check))
    {
      $file = $inputs->file('display_image_url');

      $filename = upload_file($file, 'ngos');

      $ngo->display_image_url = $filename;

      $ngo->display_image_mime = $file->getClientMimeType();
    }
    $ngo->save();
    return $ngo;
  }

  public function getNgoById($id)
  {
    return Ngo::findOrFail($id);
  }

  public function deleteNgoById($id)
  {
    $ngo = Ngo::find($id);
    $orders = Order::where('ngo_id',$ngo->id)->count(); 
    if($orders==0)
    {
      $ngo->delete();
      return true;
    }
    return false;
  }

  public function updateNgo($request, $id)
  {
    $ngo = Ngo::find($id);

    $inputs = $request->all();

    $ngo->fill($inputs);

    $new_image_check = $request->file('display_image_url');


    if(isset($new_image_check))
    {

      $file = $request->file('display_image_url');

      $filename = upload_file($file, 'ngos');

      $ngo->display_image_url = $filename;

      $ngo->display_image_mime = $file->getClientMimeType();

    }

    $ngo->save();

    return $ngo;


  }


}
<?php namespace Bueno\Repositories;

use App\Models\AdText;
use App\Models\Header;

class DbAdRepository  {

  public function getAllAdText()
  {
    return AdText::get();
  }

  public function newAdtext($inputs)
  {
    $ad_text = AdText::create($inputs);

    if($inputs['status']==1)
    {
      $this->disableAll();
      $ad_text = AdText::find($ad_text->id);
      $ad_text->status = 1;
      $ad_text->save();
    }

    return $ad_text;
  }

  public function getAdTextById($id)
  {
    return AdText::find($id);
  }

  public function disableAll()
  {
    AdText::where('status',1)->update([
      'status' => 0
    ]);
  }

  public function updateAdTextById($id,$inputs)
  {
    $ad_text = AdText::find($id);
    $ad_text->fill($inputs);
    $ad_text->save();

    if($inputs['status']==1)
    {
      $this->disableAll();
      $ad_text = AdText::find($id);
      $ad_text->status = 1;
      $ad_text->save();
    }
    return $ad_text;
  }

  /**
   * Delete an Ad Text
   *
   * @param $id
   */
  public function deleteAdTextById($id)
  {
    $ad_text = $this->getAdTextById($id);
    $ad_text->delete();
    /*if($ad_text->status)
    {
      return false;
    }
    else
    {
      $ad_text->delete();
    }*/
    return true;
  }


}
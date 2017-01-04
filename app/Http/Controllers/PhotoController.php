<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Bueno\Repositories\DbPhotoRepository as PhotoRepo;
use App\Http\Controllers\Controller;

class PhotoController extends Controller
{   
    protected $photoRepo;

    function __construct(PhotoRepo $photoRepo) {
        $this->photoRepo = $photoRepo;
    }

  /**
   * @param string $filename
   * @param $size
   * @return int
   */
  public function getMealPhoto($filename = 'default-full.jpg', $size = 'original')
    {
      $mime = 'image/jpeg';
      $image_file = get_upload_file($filename, $mime, 'meals/' . $size);

      if ( $image_file == 0 || $filename == null)
      {
        $filename = 'default-full.jpg';
        return get_upload_file($filename, $mime, 'default');
      }

      return $image_file;
    }

  /**
   * @param string $filename
   * @return int
   */
  public function getMealThumbPhoto($filename = 'default-thumbnail.jpg', $size = 'original')
    {
      $mime = 'image/jpeg';

      $image_file = get_upload_file($filename, $mime, 'meals/' . $size);

      if ($image_file == 0 || $filename == null)
      {
        $mime = 'image/jpeg';
        $filename = 'default-thumbnail.jpg';
        return get_upload_file($filename, $mime, 'default');
      }
      return $image_file;
    }

  /**
   * @param $filename
   * @return int
   */
  public function getPagePhoto($filename)
    {
      $mime = $this->photoRepo->getPagePhotoMimeType($filename);

      if (get_upload_file($filename,$mime,'webpages')==0 || $filename==null)
      {
      $mime = 'image/jpeg';
      $filename = 'bueno-kitchen.png';
      return get_upload_file($filename, $mime, 'default');
      }

      return get_upload_file($filename, $mime, 'webpages');
    }

  /**
   * @param $filename
   * @return int
   */
  public function getSliderPhoto($filename)
    {
      $mime = $this->photoRepo->getSliderPhotoMimeType($filename);

      return get_upload_file($filename, $mime, 'slider');
    }

  /**
   * @param null $filename
   * @return int
   */
  public function getUserPhoto($filename=null)
    {
      $mime = $this->photoRepo->getUserPhotoMimeType($filename);
      
      if (get_upload_file($filename,$mime,'users')==0 || $filename==null)
      {
      $mime = 'image/jpeg';
      $filename = 'default-user.png';
      return get_upload_file($filename, $mime, 'default');
      }
      else
      return get_upload_file($filename, $mime, 'users');
    }

  /**
   * @param string $filename
   * @return int
   */
  public function getGoodyPhoto($filename = 'default-full.jpg')
    {
      $mime = 'image/jpeg';

      if (get_upload_file($filename,$mime,'goody')==0 || $filename==null)
      {
        $mime = 'image/jpeg';
        $filename = 'default-thumbnail.jpg';
        return get_upload_file($filename, $mime, 'default');
      }

      return get_upload_file($filename, $mime, 'goody');
    }

  /**
   * @param string $filename
   * @return int
   */
  public function getComboPhoto($filename = 'default-full.jpg', $size = 'original')
    {
        $mime = 'image/jpeg';

        $image_file = get_upload_file($filename, $mime, 'combos/' . $size);

        if ($image_file == 0 || $filename == null)
        {
          $mime = 'image/jpeg';
          $filename = 'default-thumbnail.jpg';
          return get_upload_file($filename, $mime, 'default');
        }
        return $image_file;
    }

  /**
   * @param string $filename
   * @return int
   */
  public function getComboThumbPhoto($filename = 'default-thumbnail.jpg', $size = 'original')
  {
    $mime = 'image/jpeg';

    $image_file = get_upload_file($filename, $mime, 'combos/' . $size);

    if ($image_file == 0 || $filename == null)
    {
      $mime = 'image/jpeg';
      $filename = 'default-thumbnail.jpg';
      return get_upload_file($filename, $mime, 'default');
    }
    return $image_file;
  }

  /**
   * @param $filename
   * @return int
   */
  public function getBannerPhoto($filename)
    {
      $mime = $this->photoRepo->getBannerPhotoMimeType($filename);

      return get_upload_file($filename, $mime, 'banners');
    }

  /**
   * @param $filename
   * @return int
   */
  public function getItemPhoto($filename)
    {
      $mime = $this->photoRepo->getItemPhotoMimeType($filename);

      return get_upload_file($filename,$mime,'items');
    }

  /**
   * @param $filename
   * @return int
   */
  public function getNgoPhoto($filename)
    {
      $mime = $this->photoRepo->getNgoPhotoMimeType($filename);

      return get_upload_file($filename,$mime,'ngos');
    }
}

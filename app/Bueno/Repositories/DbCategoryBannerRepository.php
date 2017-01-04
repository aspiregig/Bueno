<?php namespace Bueno\Repositories;

use App\Models\Category;
use App\Models\CategoryBanner;

class DbCategoryBannerRepository  {

  public function getAllCategoryBanners()
  {
    return CategoryBanner::get();
  }

  public function getAllCategories()
  {
    return Category::get();
  }

  public function newBanner($inputs)
  {
    return CategoryBanner::create($inputs);
  }

  public function updateBanner($id,$inputs)
  {
    $banner = CategoryBanner::find($id);
    return $banner->fill($inputs)->save();
  }

  public function getBannerById($id)
  {
    return CategoryBanner::find($id);

  }

  public function deleteBannerById($id)
  {
    $banner = $this->getBannerById($id);
    return $banner->delete();
  }

}
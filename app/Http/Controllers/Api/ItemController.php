<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use Bueno\Transformers\ItemTransformer;

class ItemController extends ApiController
{
  /**
   * get all items
   *
   * @return mixed
   */
  public function index()
  {
    $items = Item::repo()->paginate($this->perPage);

    $inputs = request()->except('page');

    return $this->respondWithPagination($items, $inputs, new ItemTransformer);
  }

  /**
   * search items based on filters
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function getSearch()
  {
    $inputs = request()->except('page');

    $cuisine_ids = request()->has('cuisine') ? array_filter(request()->get('cuisine')) : [];
    $availability_ids = request()->has('availability') ? array_filter(request()->get('availability')) : [];
    $category_ids = request()->has('category') ? array_filter(request()->get('category')) : [];
    $price_range = request()->has('price') ? request()->get('price') : null;
    $keyword = request()->has('keyword') ? request()->get('keyword') : null;
    $sort_by = request()->has('sort') ? request()->get('sort') : null;
    $type_id = request()->has('type') ? request()->get('type') : null;
    $area_id = request()->has('area_id') ? request()->get('area_id') : null;

    $items = Item::repo()->searchMealsAndCombos($area_id, $cuisine_ids, $availability_ids, $category_ids, $price_range, $type_id, $keyword, $sort_by, $this->perPage);

    return $this->respondWithCustomPagination($items, $inputs, new ItemTransformer);
  }

  /**
   * get single item
   *
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function show($id)
  {
    $item = Item::repo()->getById($id);

    if(!$item) return $this->errorNotFound();

    return $this->respondWithItem($item, new ItemTransformer);
  }
}

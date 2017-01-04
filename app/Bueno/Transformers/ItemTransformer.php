<?php namespace Bueno\Transformers;

use App\Models\Item;
use League\Fractal\TransformerAbstract;

class ItemTransformer extends TransformerAbstract{

  /**
   * List of resources possible to include
   *
   * @var array
   */
  protected $availableIncludes = [
      'recommended'
  ];

  /**
   * List of resources to automatically include
   *
   * @var array
   */
  protected $defaultIncludes = [

  ];

  /**
   * transform items collection
   *
   * @param $items
   * @return array
   */
  public function transformItems($items)
  {
    $transform_items = [];

    foreach($items as $item)
    {
      $transform_items[] = [
        'id'  => $item->id,
        'image_url' => $item->thumb_image_url,
        'item_url'  => $item->item_url,
        'status'  => $item->itemable->status,
        'name'  => $item->itemable->name,
        'is_hot_deal'  => $item->itemable->is_hot_deal,
        'today_special' => $item->itemable->is_hot_deal ? true : false,
        'description' => $item->itemable->short_description,
        'discount_price'  => $item->itemable->discount_price,
        'original_price'  => $item->itemable->original_price,
        'serves' => $item->itemable->serves,
        'spiceLevel'  => $item->itemable->spiceLevel,
        'spiceLevelName'  => $item->itemable->spiceLevel ? $item->itemable->spiceLevel->name : null,
        'type'  => $item->itemable->type,
        'is_favorite' => $item->is_favorite,
        'slug'  => $item->itemable->slug,
        'stock' => $item->stock()
      ];
    }

    return $transform_items;
  }


  public function transformCouponItems($item)
  {
    if(empty($items)) return [];

    $items = $items->toArray();

    $transform_items = [];

    foreach($items as $item)
    {
      $transform_items[] = $this->transformCouponItem($item);
    }

    return $transform_items;
  }

  public function transformCouponItem($item)
  {
    return [
      'name'  => $item->itemable->name
    ];
  }

  public function transformApiLocalityProducts($locality)
  {
    $transforms = [];

    if($locality->firstKitchen())
    {
      foreach($locality->firstKitchen()->kitchenItems->sort(function($item){ return $item->itemable->weight; }) as $item)
      {
        if($item->itemable->status > 0)
        {
          $transforms[] = $this->transformApiLocality($item, $locality->id);
        }
      }
    }

    $transforms = array_values(array_sort($transforms, function ($item) {
      return -$item['cat_sequence'];
    }));

    return $transforms;

  }

  private function transformApiLocality($item, $locality_id)
  {
    $status = 1;

    if($item->area_stock($locality_id) == 0)
    {
      $status = 2;
    }
    else if($item->itemable->status == 2)
    {
      $status = 3;
    }


    return [
      'id'                  => (String) $item->id,
      'cat_id'              => (String) $item->itemable->category->id,
      'cat_name'            => (String) $item->itemable->category->name,
      'meal_name'           => (String) $item->itemable->name,
      'ing_id'              => null,
      'serves'              => (String) $item->itemable->serves,
      'discounted_price'    => (String) $item->itemable->discount_price,
      'original_price'      => (String) $item->itemable->original_price,
      'image'               => (String) $item->medium_image_url,
      'zoom_img'            => null,
      'description'         => (String) $item->itemable->short_description,
      'is_veg'              => $item->itemable->type != 1 ? 'Non-Vegetarian' : 'Vegetarian',
      'spice_level'         => $item->itemable->spiceLevel ? $item->itemable->spiceLevel->name : '',
      'alias'               => (String) $item->itemable->slug,
      'morder'              => (String) $item->itemable->weight,
      'cat_sequence'        => (String) $item->itemable->category->sequence,
      'mstatus'             => (String) $status,
      'created'             => (String) $item->created_at->format('Y-m-d H:i:s'),
      'updated'             => (String) $item->updated_at->format('Y-m-d H:i:s'),
      'strikethrough_price' => null,
      'hot_deals'           => (String) $item->itemable->is_hot_deal,
      'today_special'       => $item->itemable->is_hot_deal ? 'true' : 'false',
      'show_menu'           => (String) $item->itemable->is_xprs,
      'quantity'            => (String) $item->area_stock($locality_id),
      'max_order_quantity'  => $item->area_stock($locality_id) > 10 ? (String) 10 : (String) $item->area_stock($locality_id)
    ];
  }

  /**
   * Turn this item object into a generic array
   *
   * @param Item $item
   * @return array
   */
  public function transform(Item $item)
  {
    $area_id = request()->get('area_id');

    return [
        'id'                  => (int) $item->id,
        'name'                => $item->itemable->name,
        'slug'                => $item->itemable->slug,
        'discounted_price'    =>  $item->itemable->discount_price,
        'original_price'      =>  $item->itemable->original_price,
        'description'         => (String) $item->itemable->description,
        'serves'              => (String) $item->itemable->serves,
        'image'               => (String) $item->medium_image_url,
        'cat_name'            => (String) $item->itemable->category->name,
        'stock'               => $area_id ? $item->area_stock($area_id) : null
    ];
  }

  /**
   * Include Recommended item
   *
   * @return League\Fractal\Resource\Collection;
   */
  public function includeRecommended(Item $item)
  {
    $recommended = $item->recommended;

    return $this->collection($recommended, $this);
  }
}
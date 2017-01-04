<?php namespace Bueno\Transformers;

use App\Models\Favorite;
use App\Models\Item;
use League\Fractal\TransformerAbstract;

class SavedItemTransformer extends TransformerAbstract{

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
    'item'
  ];


  /**
   * transform the object into generic array
   *
   * @param Favorite $favorite
   * @return array
   */
  public function transform(Favorite $favorite)
  {
    return [
      'item_id' => $favorite->item_id,
      'user_id' => $favorite->user_id
    ];
  }

  /**
   * include item resource
   *
   * @param Favorite $favorite
   * @return \League\Fractal\Resource\Item
   */
  public function includeItem(Favorite $favorite)
  {
    return $this->item($favorite->item, new ItemTransformer);
  }
}
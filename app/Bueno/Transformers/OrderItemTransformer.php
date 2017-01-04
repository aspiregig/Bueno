<?php namespace Bueno\Transformers;

use App\Models\Item;
use App\Models\ItemOrder;
use App\Models\Order;
use League\Fractal\TransformerAbstract;

class OrderItemTransformer extends TransformerAbstract{

  /**
   * List of resources possible to include
   *
   * @var array
   */
  protected $availableIncludes = [

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
   * Turn this item object into a generic array
   *
   * @param Order $item
   * @return array
   */
  public function transform(Item $item)
  {
    return [
        'quantity'     => $item->pivot->quantity,
        'unit_price'    => $item->pivot->unit_price
    ];
  }

  /**
   * include items
   *
   * @param Order $order
   * @return \League\Fractal\Resource\Collection
   */
  public function includeItem(Item $item)
  {
    return $this->item($item, new ItemTransformer);
  }


}
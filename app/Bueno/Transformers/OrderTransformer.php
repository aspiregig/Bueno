<?php namespace Bueno\Transformers;

use App\Models\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract{

  /**
   * List of resources possible to include
   *
   * @var array
   */
  protected $availableIncludes = [
    'items'
  ];

  /**
   * List of resources to automatically include
   *
   * @var array
   */
  protected $defaultIncludes = [

  ];


  /**
   * Turn this item object into a generic array
   *
   * @param Order $item
   * @return array
   */
  public function transform(Order $order)
  {
    return [
        'id'          => (int) $order->id,
        'user_id'     => $order->user_id,
        'order_no'    => $order->order_no,
        'status'      => $order->statusText->name,
        'created_at'  => $order->created_at->format('d M Y H:i')
    ];
  }

  /**
   * include items
   *
   * @param Order $order
   * @return \League\Fractal\Resource\Collection
   */
  public function includeItems(Order $order)
  {
    return $this->collection($order->items, new OrderItemTransformer);
  }


}
<?php namespace Bueno\Transformers;

use App\Models\Cart;
use App\Models\User;
use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;

class CartTransformer extends TransformerAbstract{

  /**
   * List of resources possible to include
   *
   * @var array
   */
  protected $availableIncludes = [
      'item'
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
   * @param User $item
   * @return array
   */
  public function transform(Cart $cart)
  {
    return [
        'id'        => (int) $cart->id,
        'quantity'  => (int) $cart->quantity,
        'item_id'   => (int) $cart->item_id
    ];
  }

  /**
   * Include Recommended item
   *
   * @return League\Fractal\Resource\Collection;
   */
  public function includeItem(Cart $cart, ParamBag $params = null)
  {
    return $this->item($cart->item, new ItemTransformer);
  }


}
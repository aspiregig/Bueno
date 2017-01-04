<?php namespace Bueno\Transformers;

use App\Models\Order;
use App\Models\User;
use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract{

  /**
   * List of resources possible to include
   *
   * @var array
   */
  protected $availableIncludes = [
    'orders',
    'carts',
    'saved-items'
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
  public function transform(User $user)
  {
    return [
        'id'    => (int) $user->id,
        'first_name'  => $user->first_name,
        'last_name'   => $user->last_name,
        'phone'       => $user->phone,
        'email'       => $user->email,
        'is_verified'   => $user->mobile_verify == 1 ? 1 : 0
    ];
  }

  /**
   * Include Recommended item
   *
   * @return League\Fractal\Resource\Collection;
   */
  public function includeOrders(User $user, ParamBag $params = null)
  {
    return $this->collection($user->orders, new OrderTransformer);
  }

  /**
   * Include Recommended item
   *
   * @return League\Fractal\Resource\Collection;
   */
  public function includeCarts(User $user, ParamBag $params = null)
  {
    return $this->collection($user->cartItems, new CartTransformer);
  }

  /**
   * Include Recommended item
   *
   * @return League\Fractal\Resource\Collection;
   */
  public function includeSavedItems(User $user, ParamBag $params = null)
  {
    return $this->collection($user->saved_items, new SavedItemTransformer);
  }


}
<?php namespace Bueno\Transformers;

use App\Models\Address;
use App\Models\State;
use League\Fractal\TransformerAbstract;

class StateTransformer extends TransformerAbstract{

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

  ];


  /**
   * Turn this item object into a generic array
   *
   * @param Address $item
   * @return array
   */
  public function transform(State $state)
  {
    return [
        'id'          => (int) $state->id,
        'name'         => $state->name,
    ];
  }
}
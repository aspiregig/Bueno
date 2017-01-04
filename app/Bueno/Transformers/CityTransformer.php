<?php namespace Bueno\Transformers;

use App\Models\Address;
use App\Models\Area;
use App\Models\City;
use League\Fractal\TransformerAbstract;

class CityTransformer extends TransformerAbstract{

  /**
   * List of resources possible to include
   *
   * @var array
   */
  protected $availableIncludes = [
      'state'
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
  public function transform(City $city)
  {
    return [
        'id'          => (int) $city->id,
        'name'         => $city->name,
    ];
  }

  /**
   * @param City $city
   * @return \League\Fractal\Resource\Item
   */
  public function includeState(City $city)
  {
    return $this->item($city->state, new StateTransformer);
  }

}
<?php namespace Bueno\Transformers;

use App\Models\Address;
use App\Models\Area;
use League\Fractal\TransformerAbstract;

class AreaTransformer extends TransformerAbstract{

  /**
   * List of resources possible to include
   *
   * @var array
   */
  protected $availableIncludes = [
      'city'
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
  public function transform(Area $area)
  {
    return [
        'id'          => (int) $area->id,
        'name'         => $area->name,
    ];
  }

  /**
   * include city resource for the ara
   *
   * @param Area $area
   * @return \League\Fractal\Resource\Item
   */
  public function includeCity(Area $area)
  {
    return $this->item($area->city, new CityTransformer);
  }

}
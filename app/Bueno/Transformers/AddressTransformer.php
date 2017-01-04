<?php namespace Bueno\Transformers;

use App\Models\Address;
use App\Models\Area;
use League\Fractal\TransformerAbstract;

class AddressTransformer extends TransformerAbstract{

  /**
   * List of resources possible to include
   *
   * @var array
   */
  protected $availableIncludes = [
      'area'
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
  public function transform(Address $address)
  {
    return [
        'id'          => (int) $address->id,
        'name'         => $address->address_name,
    ];
  }

  /**
   * include area resource for the area
   *
   * @param Address $address
   * @return \League\Fractal\Resource\Item
   */
  public function includeArea(Address $address)
  {
    return $this->item($address->area, new AreaTransformer);
  }


}
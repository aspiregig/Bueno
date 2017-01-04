<?php namespace Bueno\Transformers;

class LocationTransformer{

  /**
   * @param $localities
   * @return array
   */
  public function transformApiLocalities($localities)
 {
    $transforms = [];

   foreach($localities as $locality)
   {
     $transforms[] = $this->transformApiLocality($locality);
   }

   return $transforms;
 }

  /**
   * @param $locality
   * @return array
   */
  public function transformApiLocality($locality)
  {
    return [
      'id'                => $locality->id,
      'city'              => $locality->name,
      'min_order_amount'  => (String) $locality->min_order_amount,
      'avg_delivery_time' => (String) $locality->delivery_time,
      'vat'               =>  $locality->firstKitchen() ? (String) $locality->firstKitchen()->vat : (String) 0,
      'longitude'         => $locality->longitude,
      'latitude'          => $locality->latitude
    ];
  }
}
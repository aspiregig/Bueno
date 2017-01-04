<?php namespace Bueno\Repositories;

use App\Models\Area;
use App\Models\Item;
use App\Models\Kitchen;
use App\Models\DeliveryBoy;
use App\Models\Stock;
use Bueno\Exceptions\CheckoutValidationException;
use DB;


class DbKitchenRepository  {

  public function getAllKitchens()
  {
    return Kitchen::get();
  }

  public function getKitchenById($id)
  {
    return Kitchen::find($id);
  }

  public function getAllDeliveryBoys()
  {
    return DeliveryBoy::get();
  }

  public function getDeliveryBoyById($id)
  {
    return DeliveryBoy::find($id);
  }

  public function newDeliveryBoy($inputs)
  {
     return DeliveryBoy::create($inputs);
  }

  public function updateDeliveryBoyById($id,$inputs)
  {
    $delivery_boy = $this->getDeliveryBoyById($id);
    $delivery_boy->fill($inputs)->save();
    return $delivery_boy;
  }

  public function getAllDeliveryBoysByKitchen($id)
  {
    $kitchen = $this->getKitchenById($id);
    return $kitchen->delivery_boys;
  }

  public function newKitchen($inputs)
  { 
      $kitchen = Kitchen::create($inputs->all());

    $kitchen->areas()->sync($inputs->get('areas'));

    $kitchen->managers()->sync($inputs->get('managers'));

    //stock

    $items = Item::get();

    foreach ($items as $item) {
      $new_stock = new Stock;
      $new_stock->item_id = $item->id;
      $new_stock->kitchen_id = $kitchen->id;
      $new_stock->value = 0;
      $new_stock->save();
    }

    return $kitchen;

  }

  public function updateKitchen($inputs,$id)
  { 
      $kitchen = Kitchen::find($id);

      $kitchen->areas()->sync($inputs->get('areas'));

      $kitchen->managers()->sync($inputs->get('managers'));

      $kitchen->fill($inputs->all())->save();

      return $kitchen;
  }

  public function deleteKitchenById($id)
  {
      $kitchen = Kitchen::find($id);
      if($kitchen->orders->count()!=0)
      {
        return false;
      }
      else {
        DB::table('area_kitchen')->whereKitchenId($id)->delete();
        DB::table('kitchen_managers')->whereKitchenId($id)->delete();
        $kitchen->delete();
        return true;
      }
  }

  public function newJoolehDeliveryBoy($id,$response)
  {
    $delivery_boy = DeliveryBoy::find($id);
    $jooleh_driver = json_decode($response,true);
    if(count($jooleh_driver)>1)
    {
      foreach($jooleh_driver as $driver)
      {

      }
    }
    else {

      $delivery_boy->jooleh_uid = $jooleh_driver['uid'];
      $delivery_boy->save();
    }
    return $delivery_boy;
  }

  public function deleteDeliveryBoy($id){
    $delivery_boy = DeliveryBoy::find($id);
    if($delivery_boy->orders->count()!=0)
    {
      return false;
    }
   else
   {
     $delivery_boy->delete();
     return true;
   }
  }

  public function getAreaKitchen($area_id)
  {
    return Area::find($area_id)->firstKitchen();
  }

  public function checkItemStock($cart_item, $area_id)
  {
    $area = Area::find($area_id);

    if(!$area) $stock =  0;

    $kitchen = $area->firstKitchen();

    $stock = Stock::where('item_id', $cart_item->item_id)->where('kitchen_id', $kitchen->id)->first();

    if(!$stock) return 0;

    $stock =  $stock->value;

    if($stock >= $cart_item->quantity) return true;

    return false;
  }

  public function checkIfItemsAreInStock($cart_items, $area_id)
  {
    foreach($cart_items as $cart_item)
    {
      $in_stock = $this->checkItemStock($cart_item, $area_id);

      if(!$in_stock) throw new CheckoutValidationException('Sorry the items went out of stock');
    }
  }

  public function checkItemsQuantity($cart_items)
  {
    foreach($cart_items as $cart_item)
    {
      if($cart_item->quantity > 10) throw new CheckoutValidationException('You cannot order more than 10 items');
    }
  }

}
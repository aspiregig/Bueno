<?php namespace Bueno\Repositories;

use DB;
use App\Models\Item;
use App\Models\ItemOrder;
use App\Models\Meal;
use App\Models\Stock;
use App\Models\Combo;
use App\Models\Kitchen;
use App\Models\Cuisine;
use App\Models\Category;
use App\Models\ComboMeal;
use App\Models\Availability;
use App\Models\RecommendedItem;

class DbMealRepository  {

  public function getAllMeals()
  {
    $meals =  Meal::get();
    return $meals;
  }

  public function getAllSellableItems()
  {
    $items = Item::get();

    $items = $items->filter(function($item){
      return $item->itemable_type != 'App\Models\Goody';
    });

    return $items;
  }

  public function getAllSellableMeals()
  {
    $meals = Meal::get();

    $meals = $meals->filter(function($meal){
      return $meal->is_sellable && $meal->status==1 ? true : false;
    });

    return $meals;
  }

  public function getAllSellableCombos()
  {
    $combos = Combo::get();

    $combos = $combos->filter(function($combo){
      return $combo->is_sellable && $combo->status==1 ? true:false;
    });

    return $combos;
  }

  public function getAllMealItems()
  {
    $items = Item::all();

    $items = $items->filter(function($item){
      return $item->itemable_type == 'App\Models\Meal';
    });

    return $items;
  }

  public function getAllStocks()
  {
    $stocks = Stock::with('kitchen', 'item.itemable')->get();

    return $stocks;
  }

  public function getAllItems()
  {
    $items =  Item::where('itemable_type','!=','App\Models\Goody')->get();
    return $items;
  }

  public function getAllActiveItems()
  {
    $items =  Item::get();
    $items = $items->filter( function($item){
      if($item->itemable_type!='App\Models\Goody' && $item->itemable->status)
      {
        return true;
      }
      else
        return false;
    });
    return $items;
  }


  public function getAllItemsActive()
  {
    $items =  Item::get();

    $items = $items->filter( function($item){
      if($item->itemable_type!='App\Models\Goody'&&$item->itemable->status==1)
      {
        return true;
      }
      else
        return false;
    });

    return $items;
  }


  public function getAllItemsSellable()
  {
    $items =  Item::get();

    $items = $items->filter( function($item){
      if($item->itemable_type!='App\Models\Goody'&&$item->itemable->status==1&&$item->itemable->is_sellable==1)
      {
        return true;
      }
      else
        return false;
    });

    return $items;
  }

  public function recommendedItems($id)
  {
    return Item::where("itemable_id","!=",$id)->where("itemable_type","!=","App\Model");
  }

  public function getMealById($id)
  {
    return Meal::find($id);
  }

  public function getStockById($id)
  {
    return Stock::with('kitchen', 'item.itemable')->find($id);
  }

  public function updateStockById($id, $inputs)
  {
    Stock::find($id)->update([
      'value' => $inputs['value']
    ]);
  }

  public function getItemById($id)
  {
    return Item::find($id);
  }

  public function getComboById($id)
  {
    return Combo::find($id);
  }

  public function getAllOrderCombos($id)
  {

    
  }

  public function deleteMealById($id)
  {
    $item = Item::find($id);
    $item_order = ItemOrder::where('item_id',$id)->get();
    if($item_order->count()!=0)
      return false;
    $meal = $item->itemable;
    $meal_combo = ComboMeal::where('meal_id',$meal->id)->get();
    if($meal_combo->count()!=0)
      return false;
    $meal_combo->delete();
    $meal = $this->getMealById($item->itemable->id);
    DB::table('availability_meal')->whereMealId($id)->delete();
    $item = Item::where('itemable_id',$meal->id)->where('itemable_type','App\Models\Meal')->first();
    $recommened_items = RecommendedItem::where('item_id',$item->id)->orWhere('recommended_item_id',$item->id)->delete();
    $combo_meal = ComboMeal::where('meal_id',$meal->id)->delete();
    $item = $item->delete();
    $stock = Stock::where('item_id',$id)->get;
    $meal->delete();
    $stock->delete();
    return true;
  }

  public function deleteComboById($id)
  {
    $combo = Combo::find($id);
    $item = Item::where('itemable_id',$id)->where('itemable_type','App\Models\Combo')->first();
  $item->delete();
    $combo->delete();

    return true;
  }

  public function getAllCuisines()
  {
    return Cuisine::get();
  }

  public function getAllCategories()
  {
    return Category::get();
  }

  public function getAllAvialablities()
  {
    return Availability::get();
  }

  public function getMealsByCuisineId()
  {
    $cuisine  = Cuisine::where('id','1')->first();
    return $cuisine->meals;
  }

  public function newMeal($inputs)
  {
    $items = $inputs->get('items');

    $meal = Meal::create($inputs->all());

    $file = $inputs->file('display_pic_url');

    if(isset($file))
    {

    $filename = upload_file($file, 'meals/original');

    $meal->display_pic_url = $filename;

    $meal->display_pic_mime = $file->getClientMimeType();

      resizeImage('meals', $filename );

    }
    else
    {
      $meal->display_pic_url = 'default-meal-pic.jpeg';

      $meal->display_pic_mime = 'image/jpeg';
    }

    $new_image_check = $inputs->file('thumbnail_pic_url');

    if(isset($new_image_check))
    {
      $file = $inputs->file('thumbnail_pic_url');

      $filename = upload_file($file, 'meals/original');

      $meal->thumbnail_pic_url = $filename;

      $meal->thumbnail_pic_mime = $file->getClientMimeType();

      resizeImage('meals', $filename );
    }
    else
    {
      $meal->thumbnail_pic_url = 'default-meal-pic.jpeg';

      $meal->thumbnail_pic_mime = 'image/jpeg';
    }

    $meal->save();


    $item = new Item;

    $item->itemable_id = $meal->id;
    $item->itemable_type = 'App\Models\Meal';
    $item->product_sku = $inputs->get('product_sku');
    $item->food_cost = $inputs->get('food_cost');
    $item->total_cost = $inputs->get('total_cost');
    $is_recommended = $inputs->get('is_recommended');
    if($is_recommended)
    {
      $item->is_recommended = 1;
    }
    else
    {
      $item->is_recommended = 0;
    }
    $item->save();

    $item->availabilities()->sync($inputs->get('availabilities'));

    if($items!=null)
    foreach ($items as $key => $value) {

      $recommend = new RecommendedItem;
      $recommend->item_id = $item->id;
      $recommend->recommended_item_id = $value;
      $recommend->save();

    }

    //stock

    $kitchens = Kitchen::get();

    foreach ($kitchens as $kitchen) {
      $new_stock = new Stock;
      $new_stock->item_id = $item->id;
      $new_stock->kitchen_id = $kitchen->id;
      $new_stock->value = 0;
      $new_stock->save();
    }

    return $item;
    
  }

  public function updateMeal($id,$inputs)
  {
    $meal = Meal::find($inputs->get('id'));

    $items = $inputs->get('items');

    $new_image_check = $inputs->file('display_pic_url');

    $meal->fill($inputs->all());


    if(isset($new_image_check))
    {
    $file = $inputs->file('display_pic_url');
    
    $filename = upload_file($file, 'meals/original');

      $meal->display_pic_url = $filename;

      $meal->display_pic_mime = $file->getClientMimeType();

      resizeImage('meals', $filename );

    }

    $new_image_check = $inputs->file('thumbnail_pic_url');

    if(isset($new_image_check))
    {
      $file = $inputs->file('thumbnail_pic_url');

      $filename = upload_file($file, 'meals/original');

      $meal->thumbnail_pic_url = $filename;

      $meal->thumbnail_pic_mime = $file->getClientMimeType();

      resizeImage('meals', $filename );
    }

    $meal->save();

    $item = Item::where('itemable_id',$meal->id)->where('itemable_type','App\Models\Meal')->get()->first();
    $item->product_sku = $inputs->get('product_sku');
    $item->food_cost = $inputs->get('food_cost');
    $item->total_cost = $inputs->get('total_cost');
    $is_recommended = $inputs->get('is_recommended');
    if($is_recommended)
    {
      $item->is_recommended = 1;
    }
    else
    {
      $item->is_recommended = 0;
    }

    $item->save();

    $item->availabilities()->sync($inputs->get('availabilities'));

    $recommened_items = RecommendedItem::where('item_id',$item->id)->delete();

    if($items!=null)
    foreach ($items as $key => $value) {

      $recommend = new RecommendedItem;
      $recommend->item_id = $item->id;
      $recommend->recommended_item_id = $value;
      $recommend->save();

    }


    return $item;
  }

  public function getAllCombos()
  {
    return Combo::get();
  }

  public function newCombo($inputs)
  {

    $combo = Combo::create($inputs->all());

    $new_image_check = $inputs->file('display_pic_url');

    if(isset($new_image_check))
    {
    $file = $inputs->file('display_pic_url');
    
    $filename = upload_file($file, 'combos/original');

    $combo->display_pic_url = $filename;

    $combo->display_pic_mime = $file->getClientMimeType();

      resizeImage('combos', $filename );
    }
    else
    {
      $combo->display_pic_url = 'default-meal-pic.jpeg';

      $combo->display_pic_mime = 'image/jpeg';
    }

    $new_image_check = $inputs->file('thumbnail_pic_url');

    if(isset($new_image_check))
    {
      $file = $inputs->file('thumbnail_pic_url');

      $filename = upload_file($file, 'combos/original');

      $combo->thumbnail_pic_url = $filename;

      $combo->thumbnail_pic_mime = $file->getClientMimeType();

      resizeImage('combos', $filename );

    }
    else
    {
      $combo->thumbnail_pic_url = 'default-meal-pic.jpeg';

      $combo->thumbnail_pic_mime = 'image/jpeg';
    }

    $combo->save();


    $meals = $inputs->get('meals');
    
    foreach ($meals as $meal) {
      if($meal['quantity']!='0')
      {
        ComboMeal::create(array('combo_id' => $combo->id, 'meal_id' => $meal['id'] , 'quantity' => $meal['quantity']));
      }
    }

    $item = new Item;


    $item->itemable_id = $combo->id;
    $item->itemable_type = 'App\Models\Combo';
    $item->product_sku = $inputs->get('product_sku');
    $item->food_cost = $inputs->get('food_cost');
    $item->total_cost = $inputs->get('total_cost');
    $is_recommended = $inputs->get('is_recommended');
    if($is_recommended)
    {
      $item->is_recommended = 1;
    }
    else
    {
      $item->is_recommended = 0;
    }
    $item->save();

    $kitchens = Kitchen::get();

    foreach($kitchens as $kitchen) {
      $stocks = Stock::create(['item_id' => $item->id, 'value' => 0,'kitchen_id' => $kitchen->id]);
    }
    $items = $inputs->get('items');

    if($inputs->get('availabilities')) $item->availabilities()->sync($inputs->get('availabilities'));

    if($inputs->get('items')) $item->recommendedItems()->sync($inputs->get('items'));

    return $combo;
  }

  public function updateCombo($id,$inputs)
  {
    $item = $this->getItemById($id);
    $combo = $item->itemable;
    $items = $inputs->get('items');
    $new_image_check = $inputs->file('display_pic_url');
    $meals = $inputs->get('meals');
    $combo->fill($inputs->all());
    $combo->spice_level = $inputs->get('spice_level');
    if(isset($new_image_check))
    {
    $file = $inputs->file('display_pic_url');
    
    $filename = upload_file($file, 'combos/original');

    $combo->display_pic_url = $filename;

    $combo->display_pic_mime = $file->getClientMimeType();

      resizeImage('combos', $filename );
    }

    $new_image_check = $inputs->file('thumbnail_pic_url');

    if(isset($new_image_check))
    {
      $file = $inputs->file('thumbnail_pic_url');

      $filename = upload_file($file, 'meals/original');

      $combo->thumbnail_pic_url = $filename;

      $combo->thumbnail_pic_mime = $file->getClientMimeType();

      resizeImage('combos', $filename );
    }

    $combo->save();

      $item = Item::where('itemable_id',$combo->id)->where('itemable_type','App\Models\Combo')->first();
    $item->product_sku = $inputs->get('product_sku');
    $item->food_cost = $inputs->get('food_cost');
    $item->total_cost = $inputs->get('total_cost');
    $is_recommended = $inputs->get('is_recommended');
    if($is_recommended)
    {
      $item->is_recommended = 1;
    }
    else
    {
      $item->is_recommended = 0;
    }
    $item->save();

    $item->availabilities()->sync($inputs->get('availabilities'));
    $item->recommendedItems()->sync($inputs->get('items'));

    DB::table('combo_meal')->where('combo_id', '=', $combo->id)->delete();

    $meals = $inputs->get('meals');

    foreach ($meals as $meal) {
      if($meal['quantity']!='0')
      {
        ComboMeal::create(array('combo_id' => $combo->id, 'meal_id' => $meal['id'] , 'quantity' => $meal['quantity']));
      }
    }
  
    return $combo;
  }


  /**
   * @param $data
   * @return array
   */
  public function transformForExcel($meals)
    {
      $transformData = [];

      $index = 0;

      foreach ($meals as $meal) 
      {
          $transformData[$index] = [
            'Category'                => $meal->category->name,
            'Item Name'               => $meal->name,
            'Quantity'                => 0,
            'Measurement Unit'        => $meal->original_price,
            'Unit Volume'             => $meal->original_price,
            'Rate/Unit'               => $meal->original_price,
            'Value'                   => $meal->original_price,

          ];

        $index++;
      }
      
      return $transformData;
    }

    public function getAllItemsByType($item_type)
    {
      return Item::whereItemableType($item_type)->get();
    }

    public function transformForExcelGm($meals)
    {
      $transformData = [];

      $index = 0;

      foreach ($meals as $meal) 
      {
          $transformData[$index] = [
            'Category'                => $meal->category->name,
            'Item Name'               => $meal->name,
            'Quantity'                => 0,
            'MRP Value'        => $meal->original_price,
            'Tax Value'             => $meal->original_price,
            'Invoice Value'               => $meal->original_price,
            'Discount Value'                   => $meal->original_price,
            'COGS'                   => $meal->original_price,
            'Gross Profit(Nt Tax)'                   => $meal->original_price,

          ];

        $index++;
      }
      
      return $transformData;
    }

    public function updateComboStatus($id,$status)
    {
      $combo = Combo::find($id);
      $combo->update(['status'=>$status]);
      return $combo;
    }

  public function getAllItemsNoCondition()
  {
    return Item::all();
  }


  
}
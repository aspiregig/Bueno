<?php namespace Bueno\Repositories;


use App\Models\Area;
use App\Models\BaseModel;
use App\Models\Stock;
use App\Models\Kitchen;
use App\Models\RecommendedItem;
use Bueno\Loggers\TorqusLogger;
use Bueno\Repositories\AbstractRepository;
use DB;
use App\Models\Item;
use App\Models\Order;
use App\Models\Cuisine;
use App\Models\Category;
use App\Models\ItemOrder;
use App\Models\MealFoodType;
use App\Models\Availability;
use App\Models\MealSpiceLevel;
use Bueno\Loggers\JoolehLogger;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;

class ItemRepository  extends AbstractRepository{

  function __construct(BaseModel $model)
  {
    $this->model = $model;
  }

  /**
   * get all items
   *
   * @return \Illuminate\Database\Eloquent\Collection|static[]
   */
  public function getAllItems()
  {
    return Item::all();
  }

  public  function getAllDisplayableItems()
  {
    $items = Item::get();

    $items = $items->filter(function($item){
      if($item->itemable_type!='App\Model\Goody')
      {
        return $item->itemable->status && $item->itemable->is_sellable ? true : false;
      }
      return false;
    });

    $items = $items->sortBy(function($item){
      return $item->itemable->weight;
    });

    return $items;
  }

  /**
   * search items based on filters
   *
   * @return \Illuminate\Database\Eloquent\Collection|static[]
   */
  public function searchMealsAndCombos($area_id, $cuisine_ids = [], $availability_ids = [], $category_ids = [], $price_range = null, $type_id, $keyword = null, $sort_by, $perPage = 9)
  {
    $items = [];

    if(!$area_id)
    {
      $items = collect([]);
    }else{
      $items = Area::find($area_id)->firstKitchen()->kitchenItems->filter(function($item){
        return  $item->itemable->is_sellable && $item->itemable->status != 0;
      });
    }

    if(!empty($cuisine_ids)) $items = $this->filterItemsByCuisine($items, $cuisine_ids);

    if(!empty($availability_ids)) $items = $this->filterItemsByAvailability($items, $availability_ids);

    $items = $this->sortItems($items, $sort_by);

    if(!empty($category_ids)) $items = $this->filterItemsByCategory($items, $category_ids);

    if($price_range) $items = $this->filterItemsByPriceRange($items, $price_range);

    if(!empty($type_id)) $items = $this->filterItemsByType($items, $type_id);

    if($keyword) $items = $this->filterItemsByKeyword($items, $keyword);

    $items = new LengthAwarePaginator($items, count($items), $perPage, request()->get('page'),[
        'path'  => request()->url(),
        'query' => request()->query(),
    ]);

    return $items;
  }

  /**
   * filter items by type
   *
   * @param $items
   * @param $type
   * @return mixed
   */
  public function filterItemsByType($items, $type_id)
  {
    return $items->filter(function($item) use ($type_id){
      return $item->itemable->type == $type_id;
    });
  }

  /**
   * filter items by keyword
   *
   * @param $items
   * @param $keyword
   * @return mixed
   */
  public function filterItemsByKeyword($items, $keyword)
  {
    return $items->filter(function ($item) use ($keyword) {
      return strstr( strtolower($item->itemable->name), strtolower($keyword));
    });
  }

  /**
   * filter items by price range
   *
   * @param $items
   * @param $price_range
   * @return mixed
   */
  public function filterItemsByPriceRange($items, $price_range)
  {
    $price_from = (Integer) explode(',', $price_range)['0'];
    $price_to = (Integer) explode(',', $price_range)['1'];

    return $items->filter(function($item) use ($price_from, $price_to){
      return $item->itemable->discount_price >= $price_from && $item->itemable->discount_price <= $price_to;
    });
  }

  /**
   * filters the item collection based on the cuisine id passed.
   *
   * @param $items
   * @param $cuisine_id
   * @return mixed
   */
  public function filterItemsByCuisine($items, $cuisine_ids)
  {
    return $items->filter(function($item) use ($cuisine_ids){
      return in_array($item->itemable->cuisine_id, $cuisine_ids);
    });
  }

  /**
   * filters the item collection based on the cuisine id passed.
   *
   * @param $items
   * @param $cuisine_id
   * @return mixed
   */
  public function filterItemsByCategory($items, $category_ids)
  {
    $items =  $items->filter(function($item) use ($category_ids){
      return in_array($item->itemable->category_id, $category_ids, false);
    });

    return $items->sortByDesc(function($item){
      return $item->itemable->is_hot_deal;
    });
  }

  /**
   * filters the item collection based on the cuisine id passed.
   *
   * @param $items
   * @param $cuisine_id
   * @return mixed
   */
  public function filterItemsByAvailability($items, $availability_ids)
  {
    return $items->filter(function ($item) use ($availability_ids) {
        return array_intersect($availability_ids, $item->availabilities->pluck('id')->toArray());
    });
  }

  /**
   * sort items collection by a parameter passed
   *
   * @param $items
   * @param $sort_by
   */
  public function sortItems($items, $sort_by)
  {
  
    switch($sort_by)
    {
      case 'popular':
        $items = $items->sortBy(function($item){
          return $item->orders->count();
        }, null, true);
        break;

      case 'price-high-low':
        $items = $items->sortBy(function($item){
          return $item->itemable->discount_price;
        }, null, true);
        break;

      case 'recommended' :
        $items = $items->sortBy(function($item){
            return $item->is_recommended;
        }, null, true);
        break;

      case 'price-low-high':
        $items = $items->sortBy(function($item){
          return $item->itemable->discount_price;
        }, null, false);
        break;

      default:
        $items = $items->sortBy(function($item){
          return $item->itemable->weight;
        }, null, true);
        break;
    }

    return $items;
  }
  /**
   * get all cuisines
   *
   * @return \Illuminate\Database\Eloquent\Collection|static[]
   */
  public function getAllCuisines()
  {
    return Cuisine::all();
  }

  /**
   * get all availabilities
   *
   * @return \Illuminate\Database\Eloquent\Collection|static[]
   */
  public function getAllAvailabilities()
  {
    return Availability::where('status', 1)->orderBy('sequence', "DESC")->get();
  }

  /**
   * @return \Illuminate\Database\Eloquent\Collection|static[]
   */
  public function getAllCategories()
  {
    return Category::where('status', 1)->orderBy('sequence',"DESC")->get();
  }

  /**
   * get all trending items based on orders
   *
   * @return mixed
   */
  public function getTrendingItems()
  {
    $items = $this->getAllDisplayableItems();

    $items = $items->sortByDesc(function($item){
      return $item->trendingOrders()->count();
    });
    return $items->take(5);
  }

  public function getDeTrendingItems()
  {
    $items = $this->getAllDisplayableItems();

    $items = $items->sortBy(function($item){
      return $item->trendingOrders()->count();
    });
    return $items->take(5);
  }

  /**
   * get all recommended items which belongs to the item
   *
   * @param $item_id
   * @return mixed
   */
  public function getRecommendedItems($item_id)
  {
    $item_ids = RecommendedItem::where('item_id', $item_id )
                  ->get()
                  ->pluck('recommended_item_id')
                  ->toArray();

    $items = Item::whereIn('id', $item_ids)->get();

    return $items;
  }

  public function getAllHotDeals()
  {
    $items = Item::all();

    $items = $items->filter(function ($item) {
      return $item->itemable->is_hot_deal == 1;
    });

    return $items;
  }

  public function getAllXprsMenus()
  {
    return Item::all();
  }

  public function getHotDeals($perPage = 10)
  {
    $kitchens = Kitchen::all();
    $areas = collect([]);

    foreach($kitchens as $kitchen)
    {
      $areas = $areas->merge($kitchen->areas->where('status', 1));
      $areas = $areas->sortBy(function($area){
        return $area->name;
      });
    }

    if(!session('area_id')||!in_array(session('area_id'), $areas->pluck('id')->toArray())){
      $items = [];
    }else{
      $items = Area::find(session('area_id'))->firstKitchen()->kitchenItems->filter(function($item){
        return $item->itemable->status != 0;
      });

      $items = $items->filter(function ($item) {
        return $item->itemable->is_hot_deal == 1 && $item->itemable->is_sellable == 1;
      });
    }

    $items = new LengthAwarePaginator($items, count($items), $perPage, request()->get('page'),[
        'path'  => request()->url(),
        'query' => request()->query(),
    ]);

    return $items;
  }


  /**
   *
   */
  public function getSingleItem($slug)
  {
    $items = Item::all();

    $item = $items->filter(function ($item) use ($slug)  {
      return $item->itemable->slug == $slug && $item->itemable->is_sellable == 1;
    })->first();

    return $item;
  }

  /**
   * get all meal types
   *
   * @return \Illuminate\Database\Eloquent\Collection|static[]
   */
  public function getAllMealTypes()
  {
    return MealFoodType::all();
  }

  /**
   * get all meal spice levels
   *
   * @return \Illuminate\Database\Eloquent\Collection|static[]
   */
  public function getAllMealSpiceLevels()
  {
    return MealSpiceLevel::all();
  }

  public function getCuisinesByIds($cuisine_ids)
  {
    return Cuisine::whereIn('id', $cuisine_ids)->get();
  }

  public function getAllTypes()
  {
    return MealFoodType::all();
  }

  public function getCategories($category_ids)
  {
    return Category::whereIn('id', $category_ids)->get();
  }

  public function getAvailabilitiesByIds($availability_ids)
  {
    return Availability::whereIn('id', $availability_ids)->get();
  }

  public function mealDisabled($meal)
  {
    $combos = $meal->combos;
        foreach($combos as $combo)
        {
          $combo->status = $meal->status;
          $combo->save();
        }
  }

  public function updateStockByTorqus($response,$kitchen_id)
  {
    if (isset($response[0]->dishSKU))
    {
      foreach ($response as $stock) {
        $item = Item::where('product_sku', $stock->dishSKU)->get()->first();
        if ($item) {
          $item_stock = Stock::where('item_id', $item->id)->where('kitchen_id', $kitchen_id)->get()->first();
          if ($item_stock) {
            $item_stock->value = $stock->currentStockStatus;
            $item_stock->save();
          }
        }
      }
  }
    return $response;
  }

  public function getAllKitchens()
  {
    return Kitchen::get();
  }

  public function getLowStockItems()
  {
    $low_stocks = Stock::where('value', '<=', config('bueno.stock.threshold'))->get();

    return $low_stocks;

  }

}
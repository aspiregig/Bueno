<?php namespace App\Models;

use DateTIme;

class Item extends BaseModel{

  protected  $fillable = ['itemable_id', 'itemable_type','food_cost','total_cost'];

  protected  $appends = ['image_url', 'item_url', 'spice', 'is_favorite','thumb_image_url'];

  protected $repo = 'Bueno\Repositories\ItemRepository';

  public function itemable()
  {
      return $this->morphTo();
  }

  public function getSpiceAttribute()
  {
    return $this->itemable->spiceLevel;
  }

  public function recommended()
  {
    return $this->belongsToMany('App\Models\Item', 'recommended_items', 'item_id', 'recommended_item_id');
  }

  public function recommendedItems()
  {
      return $this->belongsToMany('App\Models\Item','recommended_items','item_id','recommended_item_id');
  }

  public function orderItemInfo()
  {
    return $this->hasOne('App\Models\ItemOrder');
  }

  public function orders()
  {
    return $this->belongsToMany('App\Models\Order','item_order');
  }

  public function trendingOrders()
  {
    $orders = $this->belongsToMany('App\Models\Order','item_order')->get();
    $datetime = new DateTime();
    $datetime->modify('-7 days');
    $orders = $orders->filter(function($orders) use($datetime){
      return $orders->created_at > $datetime ? true : false;
    });
    return $orders;
  }

  public function getTotalOrdersAttribute()
  {
    return $this->orders->count();
  }

  public function getSpiceLevelAttribute()
  {
    $spice_level = null;

    if($this->itemable_type == 'App\Models\Meal')
    {
      $spice_level = $this->itemable->spice_level;
    }

    return $spice_level;
  }

  public function stock()
  {
    $area = Area::find(session('area_id'));

    if(!$area) return 0;

    $kitchen = $area->firstKitchen();

    $stock = Stock::where('item_id', $this->id)->where('kitchen_id', $kitchen->id)->first();

    if(!$stock) return 0;

    return $stock->value;
  }

  public function area_stock($area_id)
  {
    $area = Area::find($area_id);

    if(!$area) return 0;

    $kitchen = $area->firstKitchen();

    $stock = Stock::where('item_id', $this->id)->where('kitchen_id', $kitchen->id)->first();

    if(!$stock) return 0;

    return $stock->value;
  }

  public function getImageUrlAttribute()
  {
    if($this->itemable_type =='App\Models\Meal')
    {
      return route('photo.meals', ['path' => $this->itemable->display_pic_url, 'size' => 'original']);
    }

    if($this->itemable_type =='App\Models\Combo')
    {
      return route('photo.combos', ['path' => $this->itemable->display_pic_url, 'size' => 'original']);
    }

    if($this->itemable_type =='App\Models\Goody')
    {
      return route('photo.goody', ['path' => $this->itemable->display_pic_url, 'size' => 'original']);
    }
  }

  public function getMediumImageUrlAttribute()
  {
    if($this->itemable_type =='App\Models\Meal')
    {
      return route('photo.meals', ['path' => $this->itemable->display_pic_url, 'size' => 'medium']);
    }

    if($this->itemable_type =='App\Models\Combo')
    {
      return route('photo.combos', ['path' => $this->itemable->display_pic_url, 'size' => 'medium']);
    }

    if($this->itemable_type =='App\Models\Goody')
    {
      return route('photo.goody', ['path' => $this->itemable->display_pic_url, 'size' => 'medium']);
    }
  }



  public function getThumbImageUrlAttribute()
  {
    if($this->itemable_type =='App\Models\Meal')
    {
      return route('photo.meals.thumb', ['path' => $this->itemable->thumbnail_pic_url, 'size' => 'original']);
    }

    if($this->itemable_type =='App\Models\Combo')
    {
      return route('photo.combos.thumb', ['path' => $this->itemable->thumbnail_pic_url, 'size' => 'original']);
    }

    if($this->itemable_type =='App\Models\Goody')
    {
      return route('photo.goody', ['path' => $this->itemable->thumbnail_pic_url, 'size' => 'original']);
    }
  }

  public function getSmallThumbImageUrlAttribute()
  {
    if($this->itemable_type =='App\Models\Meal')
    {
      return route('photo.meals.thumb', ['path' => $this->itemable->thumbnail_pic_url, 'size' => 'small']);
    }

    if($this->itemable_type =='App\Models\Combo')
    {
      return route('photo.combos.thumb', ['path' => $this->itemable->thumbnail_pic_url, 'size' => 'small']);
    }

    if($this->itemable_type =='App\Models\Goody')
    {
      return route('photo.goody', ['path' => $this->itemable->thumbnail_pic_url, 'size' => 'small']);
    }
  }

  public function getItemUrlAttribute()
  {
    return route('items.xprs-menu.single.get', $this->itemable->slug);
  }

  public function availabilities()
  {
    return $this->belongsToMany('App\Models\Availability');
  }

  public function getIsFavoriteAttribute()
  {
    if(auth()->check() && in_array($this->id, auth()->user()->saved_items->pluck('item_id')->toArray()))
    {
      return "favourite";
    }

    return "add_to_favourite";
  }

  public function getStockByKitchenId($kitchen_id)
  {
    $stock = $this->hasMany('App\Models\Stock')->where('kitchen_id',$kitchen_id)->first();

    return $stock;
  }

  
}
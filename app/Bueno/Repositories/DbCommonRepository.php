<?php namespace Bueno\Repositories;

use App\Models\BaseModel;
use App\Models\Kitchen;
use App\Models\AdText;
use App\Models\Area;
use App\Models\BusinessQuery;
use App\Models\Category;
use App\Models\Cuisine;
use App\Models\Availability;
use App\Models\CaterLead;
use App\Models\Day;
use App\Models\Item;
use App\Models\Header;
use App\Models\HomeSlider;
use App\Models\MealFoodType;
use App\Models\Ngo;
use App\Models\OrderSource;
use App\Models\Page;
use App\Models\PaymentMode;
use App\Models\Press;
use App\Models\Query;
use App\Models\Testimonial;
use App\Models\Timing;
use App\Models\Setting;
use Bueno\Exceptions\CheckoutValidationException;
use Bueno\Mailers\SiteMailer;
use Carbon\Carbon;
use App\Models\Address;

class DbCommonRepository extends AbstractRepository{

  function __construct(BaseModel $model)
  {
    $this->model = $model;

    $this->siteMailer = new SiteMailer;
  }


  /**
   * get specific number of testimonials
   *
   * @param $perPage
   * @return mixed
   */
  public function getTestimonials($perPage)
  {
    return Testimonial::paginate($perPage);
  }

  /**
   * get specific number of active testimonials
   *
   * @param $perPage
   * @return mixed
   */
  public function getActiveTestimonials($perPage)
  {
    return Testimonial::where('status',1)->paginate($perPage);
  }

  /**
   * get specific number of banners
   *
   * @param $perPage
   * @return mixed
   */
  public function getBanners($perPage)
  {
    return Header::where('status', 1)->paginate($perPage);
  }

  /**
   * get specific number of active banners
   *
   * @param $perPage
   * @return mixed
   */
  public function getActiveBanners($perPage)
  {
    return Header::where('status',1)->paginate($perPage);
  }

  /**
   * @return mixed
   */
  public function getActiveAdText()
  {
    return AdText::where('status', 1)->get()->first();
  }

  /**
   * @throws CheckoutValidationException
   */
  public function checkIfKitchenIsOpen()
  {
    $timings = Timing::all();

    // Master Switch Checks

    $master = Setting::where('key','master_switch')->first();

    if($master->value==0)
    {
      throw new CheckoutValidationException('Kitchen is closed now');
    }

    if($timings->count()==0)
    $isOpen = 1;
    else
    $isOpen = 0;

    $now = (int) Carbon::now()->format('Hi');

    $today = (int)  Carbon::now()->format('w');

    if(!in_array($today + 1, Day::where('status', 1)->get()->pluck('id')->toArray()))
    {
      throw new CheckoutValidationException('Kitchen is closed now');
    }


    foreach ($timings as $timing)
    {
      $isOpen = 0;

      $timing_from = (int) Carbon::createFromFormat('H:i',$timing->from)->format('Hi');

      $timing_to = (int) Carbon::createFromFormat('H:i',$timing->to)->format('Hi');

      if($timing_from<=$timing_to)
      {
        if( $now >= $timing_from && $now <= $timing_to )
        {
          $isOpen = $isOpen || 1;
        }
        else{
          $isOpen = $isOpen || 0;
        }
      }
      else
      {
        if( ($now <= $timing_to && $now >= 0) || ($now >= $timing_from && $now <= 2359) )
        {
          $isOpen = $isOpen || 1;
        }
        else
        {
          $isOpen = $isOpen || 0;
        }
      }
    }
    if(!$isOpen) throw new CheckoutValidationException('Kitchen is closed now');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Collection|static[]
   */
  public function getAllNgo()
  {
    return Ngo::where('status', 1)->get();
  }

  public function createCaterLead($inputs)
  {
    return CaterLead::create($inputs);
  }

  public function getSliders($int)
  {
    return HomeSlider::where('status', 1)->orderBy('sequence')->paginate(5);
  }

  public function createContactQuery($inputs)
  {
    return Query::create([
        'name'  => $inputs['full_name'],
        'phone'  => $inputs['phone'],
        'email'  => $inputs['email'],
        'query'  => $inputs['subject'] . ' - ' . $inputs['query'],
    ]);
  }

  public function saveBusinessQuery($inputs)
  {
    return BusinessQuery::create([
        'name'  => $inputs['full_name'],
        'phone'  => $inputs['phone'],
        'email'  => $inputs['email'],
        'query'  => $inputs['subject'] . ' - ' . $inputs['query'],
    ]);
  }

  public function sendCateringMail($inputs)
  {
    $this->siteMailer->catering($inputs);
  }

  public function sendContactMail($inputs)
  {
    $this->siteMailer->contact($inputs);
  }

  public function checkIfUserIsAdmin()
  {
    $user = auth()->user();

    if($user->group->name != 'User')
    {
      throw new CheckoutValidationException('Sorry ! Admins are not allowed to place orders.');
    }

  }

  public function getAllOrderSources()
  {
    return OrderSource::all();
  }

  public function getSeoBySlug($string)
  {
    return Page::whereSlug($string)->first();
  }

  public function getAllCategories()
  {
    return Category::get();
  }

  public function newCategory($inputs)
  {
    return Category::create($inputs);
  }

  public function updateCategory($id,$inputs)
  {
    $category = Category::find($id);
    $category->update($inputs);
    return $category;
  }

  public function getCategoryById($id)
  {
    return Category::find($id);
  }

  public function deleteCategory($id)
  {
    $items = Item::get();

    $category = Category::find($id);

    $items = $items->filter(function($item) use($id){
      return $item->itemable->category_id == $id ? true : false;
    });

    if($items->count()==0)
    {
      $category->delete();
      return true;
    }

    return false;
  }

  public function getAllCuisines()
  {
    return Cuisine::get();
  }

  public function newCuisine($inputs)
  {
    return Cuisine::create($inputs);
  }

  public function updateCuisine($id,$inputs)
  {
    $cuisine = Cuisine::find($id);
    $cuisine->update($inputs);
    return $cuisine;
  }

  public function getCuisineById($id)
  {
    return Cuisine::find($id);
  }

  public function deleteCuisine($id)
  {
    $items = Item::get();

    $cuisine = Cuisine::find($id);

    $items = $items->filter(function($item) use($id){
      return $item->itemable->cuisine_id == $id ? true : false;
    });

    if($items->count()==0)
    {
      $cuisine->delete();
      return true;
    }

    return false;
  }

    public function getAllAvailabilities()
  {
    return Availability::get();
  }

  public function newAvailability($inputs)
  {
    return Availability::create($inputs);
  }

  public function updateAvailability($id,$inputs)
  {
    $availability = Availability::find($id);
    $availability->update($inputs);
    return $availability;
  }

  public function getAvailabilityById($id)
  {
    return Availability::find($id);
  }

  public function deleteAvailability($id)
  {
    $items = Item::get();

    $availability = Availability::find($id);

    $items = $items->filter(function($item) use($id){
      return $item->itemable->availability_id == $id ? true : false;
    });

    if($items->count()==0)
    {
      $availability->delete();
      return true;
    }

    return false;
  }

  public function getAllPaymentModes()
  {
    return PaymentMode::where('status', 1)->get();
  }

  public function getAreas($keyword)
  {
    // areas
    $kitchens = Kitchen::where('status', 1)->get();
    $areas = collect([]);

    foreach($kitchens as $kitchen)
    {
      $areas = $areas->merge($kitchen->areas->where('status', 1));
    }

    if($keyword)
    {
      $areas = $areas->filter(function($area) use ($keyword){
        return strstr(strtolower($area->name), strtolower($keyword));
      });
    }

    $areas = $areas->where('status', 1);

    $areas = $areas->sortBy(function($area){
        return $area->name;
    });

    return $areas;
  }

  public function checkForTotalItemsSum($cart_items)
  {
    $sum = $cart_items->sum('quantity');

    if($sum > 10) throw new CheckoutValidationException('Sorry ! You cannot order more than 10 items');
  }

  public function checkIfKitchenisActive($inputs)
  {
    $address = Address::find($inputs['address_id']);

    $area = $address->area;

    $kitchen = $area->firstKitchen();

    if(!$kitchen || $kitchen->status == 0)
    {
      throw new CheckoutValidationException('Sorry. Kitchen of the area you selected is closed now.');
    }

  }

  public function checkIfCartHasMaximumOrderAmount($cart_items)
  {
    $amount_sum = $cart_items->sum(function($cart_item){
      return $cart_item->item->itemable->discount_price;
    });

    if($amount_sum > 2500) throw new CheckoutValidationException('Sorry ! You cannot order of amount more than Rs. 2500');
  }

  public function getAllPress()
  {
    return Press::all();
  }

  public function getPressById($press_id)
  {
    return Press::find($press_id);
  }

  public function updatePress($press_id, $inputs)
  {
    $press = Press::find($press_id);

    $press->update($inputs);

    return $press;
  }

  public function newPress($inputs)
  {
    return Press::create($inputs);
  }

  public function deletePress($press_id)
  {
    return Press::find($press_id)->delete();
  }


}

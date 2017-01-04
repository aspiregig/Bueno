<?php namespace Bueno\Transformers;

use App\Models\Area;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Cuisine;
use App\Models\Ngo;
use App\Models\PaymentMode;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

class DataTransformer
{

  /**
   * List of resources possible to include
   *
   * @var array
   */
  protected $availableIncludes = [
    'ngos',
    'payments',
    'categories',
    'cuisines',
    'availabilities',
    'types',
    'offers'
  ];

  /**
   * List of resources to automatically include
   *
   * @var array
   */
  protected $defaultIncludes = [
    'ngos',
    'categories',
    'cuisines',
    'availabilities',
    'areas'
  ];


  /**
   * yes you are right, this is constructor.
   */
  function __construct(Manager $fractal)
  {
    $this->fractal = $fractal;
  }

  /**
   * transform include
   *
   * @param $includes
   * @return array
   */
  public function parseInclude($includes)
  {
    $data = [];

    $includes = array_filter(explode(',', $includes));
    $includes = array_intersect($includes, $this->availableIncludes);
    $includes = array_merge($includes, $this->defaultIncludes);
    $includes = array_unique($includes);

    foreach($includes as $include)
    {
      $method = 'include'. ucwords($include);

      $data[$include] = $this->{$method}();
    }

    return $data;
  }


  /**
   * transform the collection
   *
   * @param $resource
   * @return array
   */
  public function transform($resource)
  {
    $rootScope = $this->fractal->createData($resource);

    return $rootScope->toArray();
  }

  /**
   * includes active ngos
   *
   * @return array
   */
  public function includeNgos()
  {
    $ngos = Ngo::repo()->scopeActive()->getAll();

    $resource = new Collection($ngos, function(Ngo $ngo) {
      return [
          'id'      => (int) $ngo->id,
          'name'   => $ngo->name,
      ];
    });

   return $this->transform($resource);
  }

  /**
   * includes active payments
   *
   * @return array
   */
  public function includePayments()
  {
    $payments = PaymentMode::repo()->scopeActive()->getAll();

    $resource = new Collection($payments, function(PaymentMode $payment_mode) {
      return [
          'id'      => (int) $payment_mode->id,
          'name'   => $payment_mode->name,
      ];
    });

    return $this->transform($resource);
  }

  /**
   * include active categories
   *
   * @return array
   */
  public function includeCategories()
  {
    $categories = Category::repo()->scopeActive()->getAll();

    $resource = new Collection($categories, function(Category $category) {
      return [
          'id'      => (int) $category->id,
          'name'   => $category->name,
      ];
    });

    return $this->transform($resource);
  }

  /**
   *
   */
  public function includeTypes()
  {

  }

  public function includeAreas()
  {
    $areas = Area::repo()->getKitchensAreas();

    $resource = new Collection($areas, function(Area $area) {
      return [
          'id'        => (int) $area->id,
          'name'      => $area->name,
          'latitude'  => $area->latitude,
          'longitude' => $area->longitude
      ];
    });

    return $this->transform($resource);
  }
  /**
   * include active cuisines
   *
   * @return array
   */
  public function includeCuisines()
  {
    $cuisines = Cuisine::repo()->scopeActive()->getAll();

    $resource = new Collection($cuisines, function(Cuisine $cuisine) {
      return [
          'id'      => (int) $cuisine->id,
          'name'   => $cuisine->name,
      ];
    });

    return $this->transform($resource);
  }

  /**
   *
   */
  public function includeAvailabilities()
  {

  }

  public function includeOffers()
  {
    $coupons = Coupon::repo()->getCouponOffers();

    $resource = new Collection($coupons, function(Coupon $coupon) {
      return [
          'id'          => (int) $coupon->id,
          'code'        => $coupon->code,
          'description' => $coupon->description
      ];
    });

    return $this->transform($resource);
  }

}
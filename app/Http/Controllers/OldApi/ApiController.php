<?php

namespace App\Http\Controllers\OldApi;

use Agent;
use App\Events\OrderWasCreatedByUser;
use App\Models\Area;
use App\Models\Item;
use Bueno\Exceptions\AddToCartException;
use Exception;
use Bueno\Loggers\ApiLogger;
use App\Events\UserWasCreated;
use App\Http\Controllers\Controller;
use Bueno\Transformers\ItemTransformer;
use Bueno\Repositories\DbUserRepository;
use Bueno\Repositories\DbOrderRepository;
use Bueno\Billing\BillingServiceProvider;
use Bueno\Repositories\DbCommonRepository;
use Bueno\Repositories\DbCouponRepository;
use Bueno\Transformers\LocationTransformer;
use Bueno\Exceptions\InvalidCouponException;
use Bueno\Repositories\DbLocationRepository;
use Bueno\Exceptions\CheckoutValidationException;


class ApiController extends Controller
{
  protected $locationRepo;

  function __construct(
        BillingServiceProvider $billing,
        DbLocationRepository $locationRepo, 
        LocationTransformer $locationTransformer, 
        ItemTransformer $itemTransformer, 
        DbOrderRepository $orderRepo,
        ApiLogger $logger,
        DbUserRepository $userRepo,
        DbCommonRepository $commonRepo,
        DbCouponRepository $couponRepo
      )
  {
    $this->orderRepo = $orderRepo;

    $this->userRepo = $userRepo;

    $this->couponRepo =  $couponRepo;

    $this->locationRepo = $locationRepo;

    $this->itemTransformer = $itemTransformer;

    $this->locationTransformer = $locationTransformer;

    $this->commonRepo = $commonRepo;

    $this->logger = $logger;

    $this->billing = $billing;
  }

  /**
   * get api service by type
   *
   * @return \Illuminate\Http\JsonResponse
   */

  public function getService()
  {
    $inputs = request()->all();

    switch($inputs['service_type'])
    {
      case 'localities' :
        $data = $this->getAllLocalities();
        break;

      case 'products_by_locality' :
        $data = $this->getProductsOfLocality($inputs);
        break;

      case 'validate_coupon' :
        $data = $this->validateCoupon($inputs);
        break;

      case 'get_loyalty_points' :
        $data = $this->getUserLoyaltyPoints($inputs);
        break;

      default :
        $data = $this->handleServiceTypeError($inputs['service_type']);
        break;
    }

    $this->logger->log(json_encode($inputs));

    return $data;
  }

  /**
   * @param $inputs
   * @return array
   */
  public function getUserLoyaltyPoints($inputs)
  {
    $inputs['mobile'] = isset($inputs['mobile']) ? $inputs['mobile'] : null;

    $user = $this->userRepo->getUserByColumn('phone', $inputs['mobile']);

    if(!$user) return $this->handleServiceTypeError($inputs['service_type']);

    return [
        'service_type'  => $inputs['service_type'],
        'points'        => $user->points ? $user->points : 0,
        'referral_code' => $user->referralCoupon->status == 1 ? $user->referral_code : 'XXXXXX',
        'membership'    => $user->membership->min > 0 ? $user->membership->name : '',
        'success'       => 1,
        'error'         => 0,
    ];
  }

  /**
   * @return \Illuminate\Http\JsonResponse
   */
  public function getOffers()
  {
    $offers = $this->couponRepo->getCouponOffers();

    $data = $this->transformOffers($offers);

    return response()->json($data);
  }

  /**
   * get data.json
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function getData()
  {
    $data = '{
      "is_ordering_closed": false,
      "ordering_closed_message": "Sorry, kitchen is closed at this time. Please come back at 9 AM.",
      "contact_us_email": "info@bueno.kitchen",
      "contact_us_phone": "+911139586767",
      "max_travel_distance": 15000,
      "max_distance_from_nearest_locality": 1500 ,
      "max_travel_time": 2700,
      "max_order_items": 10,
      "min_android_version": 1020160428,
      "kitchen_locations": [
        {
        "latitude": 28.460208,
        "longitude": 77.05506
        },
        {
          "latitude": 28.490412,
        "longitude": 77.1010983
        },
        {
          "latitude": 28.5329332,
        "longitude": 77.1506649
        }
      ],
      "no_dishes_error_message": "Sorry, kitchen is closed at this time. Please come back at 9 AM.",
      "payment_methods": [
        {
        "EBS": "Online payment"
        },
        {
        "COD": "Cash on delivery"
        },
        {
        "Mobikwik": "Mobikwik wallet (10% cashback)"
        },
        {
        "PayTm": "PayTm wallet"
        }
      ],
      "payment_methods_android": [
        {
        "EBS": "Online payment"
        },
        {
        "COD": "Cash on delivery"
        },
        {
        "Mobikwik": "Mobikwik wallet (10% cashback)"
        },
        {
        "PayTm": "PayTm wallet"
        },
         {
        "PayUMoney": "PayUMoney"
        }
      ],
      "payment_methods_ios": [
          {
          "EBS": "Online payment"
          },
          {
          "COD": "Cash on delivery"
          },
          {
          "Mobikwik": "Mobikwik wallet (10% cashback)"
          }
        ]
      }';


    return response()->json(json_decode($data));
  }



  /**
   * @param $service_type
   * @return array
   */
  private function handleServiceTypeError($service_type)
  {
    return [
        'service_type' => $service_type,
        'success' => 0,
        'error' => 1,
    ];
  }

  /**
   * get all localities
   *
   * /services.php?service_type=localitites
   *
   * @return array
   */
  private function getAllLocalities()
  {
    $localities = $this->locationRepo->getKitchensAreas();

    $localities = $this->locationTransformer->transformApiLocalities($localities);

    return [
        'service_type'  => 'localities',
        'success' => 1,
        'error' => 0,
        'localities' => $localities,
    ];
  }

  /**
   * get all items in a locality
   * /services.php?service_type=products_by_locality&id=
   *
   * @param $inputs
   * @return array
   */
  private function getProductsOfLocality($inputs)
  {
    try
    {
      $this->commonRepo->checkIfKitchenIsOpen();
    }
    catch(CheckoutValidationException $e)
    {
      return $this->handleKitchenIsClosedError($inputs['service_type']);
    }

    $locality_id = isset($inputs['id']) && $inputs['id'] ? $inputs['id'] : null;

    if(is_null($locality_id)) return $this->handleServiceTypeError($inputs['service_type']);

    $locality = $this->locationRepo->getAreaById($locality_id);

    if(!$locality) return $this->handleServiceTypeError($inputs['service_type']);

    $items = $this->itemTransformer->transformApiLocalityProducts($locality);

    return [
        'service_type'  => $inputs['service_type'],
        'success' => 1,
        'error' => 0,
        'products' => $items
    ];
  }

  /**
   * @param $service_type
   * @return array
   */
  public function handleKitchenIsClosedError($service_type)
  {
    return [
        'service_type' => $service_type,
        'success' => 0,
        'error' => 1,
        'products'  => []
    ];
  }

  /**
   * @param $inputs
   * @return bool
   */
  private function checkValidationCouponInputs($inputs)
  {
    $array = ['service_type', 'items', 'couponcode', 'qtys', 'email', 'mobile', 'src', 'paymode'];

    foreach($array as $input)
    {
      if(!isset($inputs[$input]))
      {
        return false;
      }
    }

    return true;
  }

  /**
   * validate a coupons and return the total discount amount
   *
   * /services.php?service_type=validate_coupon
   *
   * @param $inputs
   * @return mixed
   */
  private function validateCoupon($inputs_data)
  {
    if(!$this->checkValidationCouponInputs($inputs_data)) return $this->handleValidateCouponError($inputs_data, 'Inputs Missing');

    $inputs = $this->setInputsForCoupon($inputs_data);
    try
    {
      $coupon = $this->orderRepo->applyCouponApi($inputs);
    }
    catch(InvalidCouponException $e)
    {
      return $this->handleValidateCouponError(request()->all(), $e->getMessage());
    }

    return [
        'service_type'  => "validate_coupon",
        'success'  => 1,
        'error'  => 0,
        'name'  => isset($inputs_data['name']) ? $inputs_data['name'] : "",
        'email'  => isset($inputs_data['email']) ? $inputs_data['email'] : "",
        'mobile'  => isset($inputs_data['mobile']) ? $inputs_data['mobile'] : "",
        'valid'  => true,
        'discount_type' => $coupon['discount_type'],
        'discount_amt'  => $coupon['discount'],
        'cashback_amt' => $coupon['cashback'],
        'description' => $coupon['description'],
        'items' => $coupon['items']
    ];
  }

  /**
   * @param $inputs
   * @param $error_message
   * @return array
   */
  public function handleValidateCouponError($inputs, $error_message)
  {
    return [
        'service_type'  => $inputs['service_type'],
        'success'  => 0,
        'error'  => 1,
        'name'  => isset($inputs['name']) ? $inputs['name'] : "",
        'email'  => isset($inputs['email']) ? $inputs['email'] : "",
        'mobile'  => isset($inputs['mobile']) ? $inputs['mobile'] : "",
        'valid'  => false,
        'error_message' => $error_message
    ];
  }

  /**
   * @param $inputs
   * @return mixed
   */
  private function setInputsForCoupon($inputs)
  {
    $cart_items = [];
    $item_ids = explode(',', $inputs['items']);
    $item_qts = explode(',', $inputs['qtys']);

    for($i = 0; $i < count($item_ids); $i++)
    {
      $cart_items[$i]['id'] = $item_ids[$i];
      $cart_items[$i]['quantity'] = $item_qts[$i];
    }

    $area_id = null;
    
    if(isset($inputs['locality']))
    {
      $area_id = Area::where('name', $inputs['locality'])->first();
    }

    return [
        'payment_mode_id' => $inputs['paymode'],
        'area_id'         => $area_id,
        'coupon_code'     => $inputs['couponcode'],
        'phone'           => $inputs['mobile'],
        'source_id'       => $inputs['src'],
        'items'           => $cart_items
    ];
  }

  /**
   * get order status and jooleh order no
   *
   * /order.php?order_id=
   *
   * @return array
   */
  public function getOrder()
  {
    $inputs = request()->all();

    $this->logger->log(json_encode($inputs));

    $order = $this->orderRepo->getOrderById($inputs['order_id']);

    if(!$order) return $this->handleErrorGetOrderApi($inputs);

    return [
        'order_id'  => $order->id,
        'order_status_type' => $order->status,
        'delivery_boy' => $order->deliveryBoy ? $order->deliveryBoy->full_name : null,
        'mobile' => $order->deliveryBoy ? $order->deliveryBoy->phone : null,
        'jooleh_order_no' => $order->joolehLog->oid,
        'order_status' => $order->statusText->name,
        'success' => 1,
        'error' => 0
    ];
  }

  /**
   * handle error json for get order request
   *
   * @param $inputs
   * @return array
   */
  private function handleErrorGetOrderApi($inputs)
  {
    return [
        'error' => 1,
        'error_msg' => "This Order id doesn't exists in database.",
        'parameters'  =>  $inputs
    ];
  }

  /**
   * creates a new order and fire events
   *
   * /index.php
   *
   * @return array
   */
  public function createOrder()
  {
    $inputs = request()->all();

    $this->logger->log('Create Order : '.json_encode($inputs));

    try
    {
      $this->checkCreateOrderInputs($inputs);

      $this->orderRepo->checkIfOrderExistsByResourceId($inputs);

      $this->checkForUsernamePassword($inputs);

      $inputs = $this->setInputsForCreateOrder($inputs);

      $this->checkForMaximumOrderAmount($inputs);

      $this->checkCreateOrderItemsStock($inputs);

      $order = $this->orderRepo->createOrderApi($inputs);
    }
    catch(CheckoutValidationException $e)
    {
      if(isset($inputs['fix']))
      {
            $order = $this->orderRepo->fixOrderInvoice($inputs);
             return [
            'tag' => $inputs['tag'],
            'success' => '1',
            'error' => '0',
            'order_id'  => $order->id,
            'fix' => 1
        ];
     }
      else
      {
      return $this->handleErrorCreateOrder($inputs, $e->getMessage());
      }
    }

    event(new OrderWasCreatedByUser($order));

    return [
        'tag' => $inputs['tag'],
        'success' => '1',
        'error' => '0',
        'order_id'  => $order->id
    ];
  }

  /**
   * @param $inputs
   * @param $error
   * @return array
   */
  private function handleErrorCreateOrder($inputs, $error)
  {
    return [
        'tag' => isset($inputs['tag']) ? $inputs['tag'] : '',
        'success' => 0,
        'error' => 1,
        'error_message' => $error,
        'parameters'  => $inputs
    ];
  }

  /**
   * @param $inputs
   * @throws CheckoutValidationException
   */
  private function checkCreateOrderInputs($inputs)
  {
    $array = ['username', 'password', 'order_no', 'tag', 'email', 'name', 'mobile', 'locality', 'address', 'order_amount', 'vat', 'paymode', 'orders'];

    foreach($array as $input)
    {
      if(!isset($inputs[$input]))  throw new CheckoutValidationException( $input . ' Inputs Missing');
    }
  }

  /**
   * @param $inputs
   * @throws CheckoutValidationException
   */
  private function checkForUsernamePassword($inputs)
  {
    if( md5("bueno2015") != $inputs['username'] && md5("api#1008") != $inputs['password'])
    {
      throw new CheckoutValidationException('Username and password are wrong');
    }
  }

  /**
   * @param $inputs
   * @return mixed
   */
  private function setInputsForCreateOrder($inputs)
  {
    $inputs['landmark'] = isset($inputs['landmark']) ? $inputs['landmark'] : '';
    $inputs['voucher_amount'] = isset($inputs['voucher_amount']) ? $inputs['voucher_amount'] : 0;
    $inputs['delivery_fee'] = isset($inputs['delivery_fee']) ? $inputs['delivery_fee'] : 0;
    $inputs['instruction'] = isset($inputs['instruction']) ? $inputs['instruction'] : '';
    $inputs['points'] = isset($inputs['redeem_points']) ? $inputs['redeem_points'] : 0;

    return $inputs;
  }

  /**
   * @param $offers
   * @return array
   */
  private function transformOffers($offers)
  {
    $data = [];

    foreach($offers as $offer)
    {
      $data['offers'][] = [
        $offer->code  => $offer->description
      ];
    }

    return $data;
  }

  /**
   * @param $payment_mode_id
   * @return $this|array
   */
  public function buildChecksum($payment_mode_id)
  {
    $payment = $this->billing->resolve();

    $inputs = request()->all();

    return $payment->buildChecksumAPI($inputs);
  }

  /**
   * @param $payment_mode_id
   * @return $this
   */
  public function verifyChecksum($payment_mode_id)
  {
    $inputs = request()->all();

    $payment = $this->billing->resolve();

    return $payment->verifyChecksumAPI($inputs);
  }

  private function checkCreateOrderItemsStock($inputs)
  {
    if($inputs['paymode'] == 2)
    {
      $area = Area::where('name', $inputs['locality'])->first();

      foreach($inputs['orders'] as $meal)
      {
        $item = Item::where('id', $meal['meal_id'])->first();

        if(!$item) throw new CheckoutValidationException('Sorry the item you selected is either out of stock or disabled.');

        if($item && $item->itemable->status != 1) throw new CheckoutValidationException('Sorry '. $item->itemable->name .' is either out of stock or disabled.');

        if($item->area_stock($area->id) < $meal['qty'])
        {
          throw new CheckoutValidationException('Sorry '. $item->itemable->name .' is out of stock.');
        }
      }
    }
  }

  private function checkForMaximumOrderAmount($inputs)
  {
    $sum = 0;

    foreach($inputs['orders'] as $item)
    {
      $sum = $item['qty'] * $item['discount_price'];
    }

    if($sum > 2500) throw new CheckoutValidationException('Sorry ! You cannot order of amount more than Rs. 2500');

  }

}

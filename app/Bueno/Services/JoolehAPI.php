<?php namespace Bueno\Services;

use App\Models\Order;
use App\Models\JoolehLog;
use Bueno\Loggers\JoolehLogger;
use GuzzleHttp\Client;
use Bueno\Repositories\DbOrderRepository as OrderRepo;
use GuzzleHttp\Exception\ClientException;
use Exception;

class JoolehAPI
{ 
  /**
   * Guzzle Client Instance
   * 
   */
  protected $client,$orderRepo;

  protected $logger;



  /**
   * base url for the Jooleh API
   * 
   * @var string
   */
  protected $url = "https://jooleh.com/api/v1/admin";

  function __construct(Client $client,OrderRepo $orderRepo,JoolehLogger $logger)
  {
    $this->client = $client;
    $this->logger = $logger;
    $this->orderRepo = $orderRepo;
  }


  /**
   * @param $order
   */
  public function createOrder(Order $order)
  {
    $request_url = $this->url . "/orders/create";
    try{
    $response = $this->client->request('POST', $request_url, [
        'headers'=> [
          'X-Admin-Token' => $order->kitchen->jooleh_token,
          'X-Admin-Username' => $order->kitchen->jooleh_username,
        ],
        'form_params' => [
          'mobile' => $order->user->phone,
          'bill_amount' => $order->paymentInfo->amount,
          'order_number' => $order->id,
          'customer_name' => $order->user->full_name,
          'customer_address' => $order->delivery_address,
          'customer_locality' => $order->area ? $order->area->name : $order->delivery_address,
          'customer_email' => $order->user->email,
          'payment_mode' => ($order->payment_mode_id==2 ? 'COD' : 'Online'),
          'customer_latitude' => $order->latitude,
          'customer_longitude' => $order->longitude,
          'order_info' => $this->orderItemJsonFormat($order->items)
        ],
        'timeout' => 10
    ]);
  }
  catch(Exception $e)
  {
    $this->logger->log($e->getMessage());
    $response = false;
  }  
    return $response;
  }

  public function orderItemJsonFormat($items)
  {
    $counter = 0;

    $comma = 0;
    foreach($items as $item)
    {
      $items_array[] =[ 
      "name" => $item->itemable->name,
      "qty" => $item->pivot->quantity,
      "price" => $item->pivot->unit_price
      ];      
    }
     $order_items['order_items'] = $items_array;
    return json_encode($order_items);
  }

  public function updateOrder($order)
  {
    if($order->joolehLog) {
      $request_url = $this->url . "/orders/" . $order->joolehLog->oid;

      try{
        $response = $this->client->request('PUT', $request_url, [
          'headers' => [
              'X-Admin-Token' => $order->kitchen->jooleh_token,
              'X-Admin-Username' => $order->kitchen->jooleh_username,
          ],
          'form_params' => [
              'status' => config('bueno.jooleh_status.2'),
              'dispatch_status' => 'Picked'
          ],
          'timeout' => 10
      ]);
          $this->logger->log('Jooleh :: Marked order dispatched '.date('m d Y').' order-id '.$order->id.' response : '.$response->getBody()->getContents());
      }
  catch(Exception $e)
  {
    $this->logger->log($e->getMessage());
    $response = null;
  }

      return $response;
    }
    return "false";

  }

    public function cancelOrder($order)
    {
        if($order->joolehLog) {
            $request_url = $this->url . "/orders/" . $order->joolehLog->oid;

            try{
                $response = $this->client->request('DELETE', $request_url, [
                    'headers' => [
                        'X-Admin-Token' => $order->kitchen->jooleh_token,
                        'X-Admin-Username' => $order->kitchen->jooleh_username,
                    ],
                    'form_params' => [
                        'reason' => 'SystemIssue'
                    ],
                    'timeout' => 10
                ]);
                $this->logger->log('Jooleh :: Marked order cancelled '.date('m d Y').' order-id '.$order->id.' response : '.$response->getBody()->getContents());
            }
            catch(Exception $e)
            {
                $this->logger->log($e->getMessage());
                $response = null;
            }

            return $response;
        }
        return "false";

    }

  public function getOrderDetails($order)
  {
    if($order->joolehLog) {
      $request_url = $this->url . "/orders/" . $order->joolehLog->oid;

      try {
        $response = $this->client->request('GET', $request_url, [
            'headers' => [
                'X-Admin-Token' => $order->kitchen->jooleh_token,
                'X-Admin-Username' => $order->kitchen->jooleh_username,
            ],
            'timeout' => 10
        ]);
      } catch (Exception $e) {
        $this->logger->log($e->getMessage());
        $response = false;
      }
      return $response;
    }
    return false;

  }

  public function confirmOrder($order)
  {
    if($order->joolehLog){
    $request_url = $this->url ."/orders/". $order->joolehLog->oid;
    try{
    $response = $this->client->request('PUT', $request_url, [
        'headers'=> [
            'X-Admin-Token' => $order->kitchen->jooleh_token,
            'X-Admin-Username' => $order->kitchen->jooleh_username,
        ],
        'form_params' => [
            'oid' => $order->joolehLog->oid,
            'status' => config('bueno.jooleh_status.1'),
        ],
        'timeout' => 10
    ]);
  }
  catch(Exception $e)
  {
    $this->logger->log($e->getMessage());
    $response = null;
  }

    return $response;
    }

    return "false";

  }

  public function dispatchedOrder($order,$status)
  {
    $this->url .="/orders/". $order->oid;

    $response = $this->client->request('PUT', $this->url, [
        'headers'=> [
            'X-Admin-Token' => config('bueno.jooleh.admin_token'),
            'X-Admin-Username' => config('bueno.jooleh.admin_username')
        ],
        'form_params' => [
            'status' => $status
        ],
        'timeout' => 10
    ]);

    return $response;

  }



  public function getOrders()
  {
    $this->url .= "/orders?page=1&per_page=20&offset=0&status=Dispatched";
    $response = $this->client->request('GET', $this->url, [
        'headers'=> [
            'X-Admin-Token' => config('bueno.jooleh.admin_token'),
            'X-Admin-Username' => config('bueno.jooleh.admin_username'),
        ],
        'timeout' => 10
    ]);

    return $response;
  }


  /**
   *
   *
   * @return jooleh_uid
   */
  public function createDeliveryBoy($delivery_boy)
  {

    $this->url .= "/users";

    try {
      $response = $this->client->request('POST', $this->url, [
          'headers' => [
              'X-Admin-Token' => config('bueno.jooleh.admin_token'),
              'X-Admin-Username' => config('bueno.jooleh.admin_username')
          ],
          'form_params' => [
              'name' => $delivery_boy->full_name,
              'username' => $delivery_boy->phone,
              'password' => $delivery_boy->jooleh_pass,
          ],
          'timeout' => 10
      ]);
    }
  catch(ClientException $e)
   {

     $response = $this->client->request('GET', $this->url, [
         'headers'=> [
             'X-Admin-Token' => config('bueno.jooleh.admin_token'),
             'X-Admin-Username' => config('bueno.jooleh.admin_username')
         ],
         'timeout' => 10
     ]);
   }

    return $response;
  }

  /**
   *
   *
   * @return jooleh_uid
   */
  public function updateDeliveryBoy($delivery_boy)
  {
    $this->url .= "/users/".$delivery_boy->jooleh_uid;


     $response = $this->client->request('PUT', $this->url, [
         'headers'=> [
             'X-Admin-Token' => config('bueno.jooleh.admin_token'),
             'X-Admin-Username' => config('bueno.jooleh.admin_username')
         ],
         'form_params' => [
             'name' => $delivery_boy->full_name,
             'password' => $delivery_boy->jooleh_pass,
             'status' => 'Active',
         ],
         'timeout' => 10
     ]);

    return $response;
  }
}
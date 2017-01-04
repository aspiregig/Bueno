<?php

namespace App\Http\Controllers;

use App\Events\OrderWasSettled;
use App\Http\Controllers\Controller;
use Bueno\Loggers\JoolehLogger;
use Bueno\Repositories\DbOrderRepository as OrderRepo;
use Bueno\Repositories\ItemRepository as ItemRepo;

class APIController extends Controller
{

  protected  $orderRepo,$itemRepo,$logger;

  function __construct(OrderRepo $orderRepo,ItemRepo $itemRepo,JoolehLogger $logger)
  {
    $this->orderRepo = $orderRepo;
    $this->itemRepo = $itemRepo;
    $this->logger = $logger;
  }


public function joolehNotification($uname)
{

  $response =  request()->all();

  $message = 'Delivered Notification Post : ';

  if($uname=='7757939e4ef562d01d6a8235470e4c613a0ad90a')
  {
    $order =  $this->orderRepo->updateOrderJooleh($response);
      if($order)
        event(new OrderWasSettled($order));
  }
  else
    $message = 'Unautharized Uname : ';

  $this->logger->log('Update Order '. $message . json_encode($response));

  return $response;
}

public function Torqus($kitchen_id)
{
  $response =  request()->all();

  return $this->itemRepo->updateStockByTorqus($response,$kitchen_id);

}

}


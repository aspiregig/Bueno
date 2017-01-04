<?php

namespace App\Listeners;

use Bueno\Services\JoolehAPI as Jooleh;
use App\Events\OrderWasCreatedByUser;
use Bueno\Loggers\JoolehLogger;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Bueno\Repositories\DbOrderRepository as OrderRepo;


class SendNewOrderRequestToJooleh
{
  protected  $jooleh,$orderRepo;
  protected $logger;

  /**
   * Create the event listener.
   *
   * @return void
   */
  public function __construct(Jooleh $jooleh,OrderRepo $orderRepo,JoolehLogger $logger)
  {
    $this->jooleh = $jooleh;
    $this->orderRepo = $orderRepo;
    $this->logger = $logger;    
  }

  /**
   * Handle the event.
   *
   * @param  OrderWasCreatedByUser  $event
   * @return void
   */
  public function handle(OrderWasCreatedByUser $event)
  {
    $response = $this->jooleh->createOrder($event->order);
    if($response)
    {
      $response = $response->getBody()->getContents();
    $this->orderRepo->newJoolehOrder($response);
    $this->jooleh->confirmOrder($event->order);
  }
    $this->logger->log('Jooleh '.date('m d Y').' order-id '.$event->order->id.' response : ' .$response);
  }
}

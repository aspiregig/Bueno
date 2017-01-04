<?php namespace Bueno\Services;


use Bueno\Loggers\SmsLogger;
use GuzzleHttp\Client;
use Bueno\Repositories\DbOrderRepository as OrderRepo;

class SmsAPI
{
  /**
   * Guzzle Client Instance
   *
   */

  protected $client;

  protected $logger;

  /**
   * base url for the SMS API
   *
   * @var string
   */
  protected $url = "http://alerts.solutionsinfini.com//api/v3/index.php";

  function __construct(Client $client, SmsLogger $logger)
  {
    $this->logger = $logger;

    $this->client = $client;
  }

  /**
   *  Sends the curl request to SMS API  to send sms to
   *  specified phone and with specfifed message
   */
  public function send($phone, $message)
  {
    if(env('APP_ENV') != 'production') {
      $phone = '9899489788';
    }

    $this->buildUrl($phone, $message);

    $response = $this->client->request('GET', $this->url);

    $this->logger->log('Message sent to ' . $phone . ', Response : ' . $response->getBody()->getContents() . ', Message :' . $message );

  }

  /**
   * Builds the SMS url
   *
   * @param  integer 	$mobile_no 	Mobile No. of the reciecer
   * @param  string 	$message   	Message to be send
   */
  protected function buildUrl($mobile_no, $message)
  {
    $this->url .=  "?method=sms&api_key=" . config('bueno.sms_api.password') . "&sender=BUENOF&to=" . $mobile_no . "&format=json&message=" . urlencode($message);
  }
}
<?php namespace Bueno\Services;


use Bueno\Loggers\SmsLogger;
use Bueno\Loggers\VoiceOtpLogger;
use GuzzleHttp\Client;

class VoiceOtpApi
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
  protected $url = "https://2factor.in/API/V1/";

  function __construct(Client $client, VoiceOtpLogger $logger)
  {
    $this->logger = $logger;

    $this->client = $client;
  }

  /**
   *  Sends the curl request to SMS API  to send sms to
   *  specified phone and with specfifed message
   */
  public function send($mobile_no, $otp)
  {
    $this->buildUrl($mobile_no, $otp);

    $response = $this->client->request('GET', $this->url);

    $this->logger->log('Message sent to ' . $mobile_no . ', Response : ' . $response->getBody()->getContents() . ', Otp :' . $otp );
  }

  /**
   * Builds the SMS url
   *
   * @param  integer 	$mobile_no 	Mobile No. of the reciecer
   * @param  string 	$message   	Message to be send
   */
  protected function buildUrl($mobile_no, $otp)
  {
    $this->url .=  config('bueno.voice_otp_api.key') . "/VOICE/" .  $mobile_no . "/" . $otp;
  }
}
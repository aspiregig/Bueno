<?php namespace Bueno\Services;

use App\Models\JoolehLog;
use Bueno\Repositories\DbOrderRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class TorqusApi
{ 
  /**
   * Guzzle Client Instance
   * 
   */
  protected $client;




  /**
   * base url for the Jooleh API
   * 
   * @var string
   */
  protected $url = "https://scm.torqus.com/rest/v0.1/onlineAppWebService";

  function __construct(Client $client)
  {
    $this->client = $client;
  }

  public function getAllSites()
  {
    $request_url = $this->url . "/getAllSites";

    $response = $this->client->request('POST', $request_url,
        ['json' => ["userName" => config('bueno.torqus.username'),
                  "password" => config('bueno.torqus.password'),
                  "type" => config('bueno.torqus.type'),
                  "companyId" => config('bueno.torqus.companyId'),]]
    );
    return $response;
  }

  public function getAllDishes($spoke_id=null)
  {
    $request_url = 'http://52.24.238.226:8080/SCM/rest/v0.1/onlineAppWebService/getAllDishes';

    if($spoke_id==null)
    {
      $spoke_id =config('bueno.torqus.companyId');
    }

    $response = $this->client->request('POST', $request_url,
        ['json' => ["userName" => config('bueno.torqus.username'),
      "password" => config('bueno.torqus.password'),
      "type" => config('bueno.torqus.type'),
      "siteId" =>$spoke_id ]]
    );
    return $response;
  }

  public function addOrder()
  {
    $request_url = $this->url . "/getAllSites";

    $response = $this->client->request('POST', $request_url, ['json' => ["userName" => config('bueno.torqus.username'), "password" => config('bueno.torqus.password'),"type" => config('bueno.torqus.type'),"companyId" => config('bueno.torqus.companyId')]]
    );
    return $response;
  }

}
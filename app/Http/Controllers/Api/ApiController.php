<?php

namespace App\Http\Controllers\Api;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Http\Controllers\Controller;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class ApiController extends Controller
{
  /** default status code
   *
   * @var int
   */
  protected $statusCode = 200;

  /**
   * default per page limit
   *
   * @var int
   */
  protected $perPage = 15;

  /**
   *  list of http error codes
   */
  const HTTP_BAD_REQUEST      = 400;
  const HTTP_UNAUTHORIZED     = 401;
  const HTTP_FORBIDDEN        = 403;
  const HTTP_NOT_FOUND        = 404;
  const HTTP_INTERNAL_ERROR   = 500;


  /**
   * Default status codes translation table.
   *
   * @var array
   */
  public static $statusText = [
      self::HTTP_BAD_REQUEST      => 'Bad Request',
      self::HTTP_UNAUTHORIZED     => 'Unauthorized',
      self::HTTP_FORBIDDEN        => 'Forbidden',
      self::HTTP_NOT_FOUND        => 'Resource Not Found',
      self::HTTP_INTERNAL_ERROR   => 'Some Technical Error Occurred'
  ];

  function __construct()
  {
    $this->fractal = new Manager;

    // parse Includes
    if (request()->get('include')) $this->fractal->parseIncludes(request()->get('include'));

    // set per page limit
    if( request()->get('limit')) $this->perPage = request()->get('limit');
  }


  /**
   * Getter for statusCode
   *
   * @return int
   */
  public function getStatusCode()
  {
    return $this->statusCode;
  }

  /**
   * Setter for statusCode
   *
   * @param int $statusCode Value to set
   *
   * @return self
   */
  public function setStatusCode($statusCode)
  {
    $this->statusCode = $statusCode;

    return $this;
  }

  /**
   * respond with a single item
   *
   * @param $item
   * @param $callback
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondWithItem($item, $callback)
  {
    $resource = new Item($item, $callback);

    $rootScope = $this->fractal->createData($resource);

    return $this->respondWithArray($rootScope->toArray());
  }

  /**
   * respond with a collection and meta ( pagination meta )
   *
   * @param $collection
   * @param $callback
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondWithPagination($collection, $inputs, $callback)
  {
    $paginator = $collection;
    
    $paginator->appends($inputs);

    $collection = $paginator->getCollection();

    $resource = new Collection($collection, $callback);

    $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

    $rootScope = $this->fractal->createData($resource);

    return $this->respondWithArray($rootScope->toArray());
  }

  /**
   * @param $collection
   * @param $inputs
   * @param $callback
   * @return \Illuminate\Http\JsonResponse
   */
  public function respondWithCustomPagination($collection, $inputs, $callback)
  {
    $paginator = $collection;

    $paginator->appends($inputs);

    $collection = $paginator->getCollection();

    $resource = new Collection($collection->slice($this->perPage*($paginator->currentPage() - 1), $this->perPage), $callback);

    $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

    $rootScope = $this->fractal->createData($resource);

    return $this->respondWithArray($rootScope->toArray());
  }

  /**
   * respond with collection of items
   *
   * @param $collection
   * @param $callback
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondWithCollection($collection, $callback)
  {
    $resource = new Collection($collection, $callback);

    $rootScope = $this->fractal->createData($resource);

    return $this->respondWithArray($rootScope->toArray());
  }

  /**
   * respond with array
   *
   * @param array $array
   * @param array $headers
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondWithArray(array $array, array $headers = [])
  {
    $response = response()->json($array, $this->statusCode, $headers);

    $response->header('Content-Type', 'application/json');

    return $response;
  }

  /**
   * respond with array of errors
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function respondWithErrors($errors)
  {
    return $this->setStatusCode(self::HTTP_UNAUTHORIZED)->respond([
        'errors' => is_array($errors) ? $errors : $this->transformErrorToArray($errors)
    ]);
  }

  /**
   * @param $error
   * @return array
   */
  public function transformErrorToArray($error)
  {
    return [ [ 'message' => $error ] ];
  }

  /**
   * Generates a Response with a 403 HTTP header and a given message.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function errorForbidden($message = null)
  {
    $message = $message ? $message : self::$statusText[self::HTTP_FORBIDDEN];

    return $this->setStatusCode(self::HTTP_FORBIDDEN)
        ->respondWithErrors($message);
  }

  /**
   * Generates a Response with a 500 HTTP header and a given message.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function errorInternalError($message = null)
  {
    $message = $message ? $message : self::$statusText[self::HTTP_INTERNAL_ERROR];

    return $this->setStatusCode(self::HTTP_INTERNAL_ERROR)
        ->respondWithErrors($message);
  }

  /**
   * Generates a Response with a 404 HTTP header and a given message.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function errorNotFound($message = null)
  {
    $message = $message ? $message : self::$statusText[self::HTTP_NOT_FOUND];

    return $this->setStatusCode(self::HTTP_NOT_FOUND)
        ->respondWithErrors($message);
  }

  /**
   * Generates a Response with a 401 HTTP header and a given message.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function errorUnauthorized($message = null)
  {
    $message = $message ? $message : self::$statusText[self::HTTP_UNAUTHORIZED];

    return $this->setStatusCode(self::HTTP_UNAUTHORIZED)
        ->respondWithErrors($message);
  }

  /**
   * Generates a Response with a 400 HTTP header and a given message.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function errorWrongArgs($message)
  {
    $message = $message ? $message : self::$statusText[self::HTTP_BAD_REQUEST];

    return $this->setStatusCode(self::HTTP_BAD_REQUEST)
        ->respondWithErrors($message);
  }

  /**
   * add version info to api
   *
   * @param $data
   * @return mixed
   */
  public function addVersionInfo($data)
  {
    $data['minimumVersion'] = env('API_MINIMUM_VERSION');
   $data['currentVersion'] = env('API_CURRENT_VERSION');

    return $data;
  }

}

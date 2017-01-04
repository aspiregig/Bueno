<?php

namespace App\Http\Controllers;

use Twitter;
use SEOMeta;
use OpenGraph;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  /**
   * @var int
   */
  protected $statusCode = 200;

  public function setSeo($title = '', $description = '', $image = '')
  {
    // OG Tags
    OpenGraph::setTitle($title);
    OpenGraph::setDescription($description);
    $image ? OpenGraph::addImage($image) : OpenGraph::addImage(url() . '/images/logo.png');
    OpenGraph::setUrl(request()->fullUrl());

    // SEO Meta
    SEOMeta::setTitle($title);
    SEOMeta::setDescription($description);

    // Twitter Tags
    Twitter::setTitle($title);
    Twitter::setSite(request()->fullUrl());

  }

  /**
   * @return int
   */
  public function getStatusCode()
  {
    return $this->statusCode;
  }

  /**
   * @param int $statusCode
   */
  public function setStatusCode($statusCode)
  {
    $this->statusCode = $statusCode;

    return $this;
  }

  /**
   * @param string $message
   * @return \Illuminate\Http\JsonResponse
   */
  public function respondNotFound($message = "Not Found !")
  {
    return $this->setStatusCode(404)->respondWithErrors($message);
  }

  /**
   * @param string $message
   * @return \Illuminate\Http\JsonResponse
   */
  public function respondNotAuthorized($message = "Not Authorized !")
  {
    return $this->setStatusCode(401)->respondWithErrors($message);
  }

  /**
   * @param $data
   * @param array $headers
   * @return \Illuminate\Http\JsonResponse
   */
  public function respond($data, $headers = [])
  {
    return response()->json($data, $this->getStatusCode(), $headers);
  }

  /**
   * @param $data
   * @return \Illuminate\Http\JsonResponse
   */
  public function respondWithSuccess($data)
  {
    return $this->respond([
        'success' => true,
        'data'  => $data,
        'errors' => null
    ]);
  }

  /**
   * @param $message
   * @return \Illuminate\Http\JsonResponse
   */
  public function respondWithErrors($errors)
  {
    return $this->respond([
        'success' => false,
        'data'  => null,
        'errors' => is_array($errors) ? $errors : $this->transformErrorToArray($errors)
    ]);
  }

  /**
   * @param $error
   * @return array
   */
  public function transformErrorToArray($error)
  {
    return [
        [
            'message' => $error
        ]
    ];
  }
}



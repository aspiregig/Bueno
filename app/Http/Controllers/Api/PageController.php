<?php

namespace App\Http\Controllers\Api;

use Bueno\Transformers\DataTransformer;

class PageController extends ApiController
{
  /**
   * get all basic info
   *
   * @return mixed
   */
  public function index()
  {
    $includes = request()->get('include') ?: '';

    $transformer = new DataTransformer($this->fractal);

    $data = $transformer->parseInclude($includes);

    $data = $this->addVersionInfo($data);

    return $this->respondWithArray([
      'data'  => $data
    ]);
  }
}

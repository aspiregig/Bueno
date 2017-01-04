<?php

namespace App\Http\Controllers\Api;

use Bueno\Repositories\DbCommonRepository;
use Bueno\Transformers\AreaTransformer;

/**
 * Class LocationController
 * @package App\Http\Controllers\Api
 */
class LocationController extends ApiController
{
  /**
   * @param DbCommonRepository $commonRepo
   */
  function __construct(DbCommonRepository $commonRepo)
  {
    parent::__construct();

    $this->commonRepo = $commonRepo;
  }

  /**
   * get all areas assigned to any kitchen
   *
   * @return mixed
   */
  public function getAreas()
  {
    $inputs = request()->all();

    $keyword = isset($inputs['keyword']) ? $inputs['keyword'] : '';

    $areas = $this->commonRepo->getAreas($keyword);

    return $this->respondWithCollection($areas, new AreaTransformer);
  }
}

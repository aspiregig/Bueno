<?php

namespace App\Http\Controllers;

use Bueno\Repositories\DbLocationRepository;
use Bueno\Transformers\ItemTransformer;
use Flash;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bueno\Repositories\ItemRepository;
use Bueno\Validations\ValidationException;
use SEOMeta;
use Bueno\Repositories\DbCommonRepository;
use Breadcrumbs;
use OpenGraph;


/**
 * Class ItemsController
 * @package App\Http\Controllers
 */
class ItemsController extends Controller
{
  /**
   * @var ItemRepository
   */
  protected $itemRepo;

  /**
   * @param ItemRepository $itemRepo
   */
  function __construct(DbCommonRepository $commonRepo, ItemRepository $itemRepo, DbLocationRepository $locationRepo, ItemTransformer $transformer)
  {
    $this->itemRepo = $itemRepo;

    $this->commonRepo = $commonRepo;

    $this->transformer = $transformer;

    $this->locationRepo = $locationRepo;

    //$this->middleware('area');
  }

  /**
   * search items with filters
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getXprsMenu()
  {
    $seo = $this->commonRepo->getSeoBySlug('global-menu');

    if($seo) $this->setSeo($seo->meta_title, $seo->meta_description,  $seo->meta_image_url);

    $inputs = request()->all();

    $filters = [
        'cuisines'        => $this->itemRepo->getAllCuisines(),
        'availabilities'  => $this->itemRepo->getAllAvailabilities(),
        'categories'      => $this->itemRepo->getAllCategories(),
        'types'           => $this->itemRepo->getAllMealTypes()
    ];

    $area = $this->locationRepo->getSessionArea();

    // setting default filters
    $cuisine_ids = request()->has('cuisine') ? array_filter(request()->get('cuisine')) : [];
    $availability_ids = request()->has('availability') ? array_filter(request()->get('availability')) : [];
    $category_ids = request()->has('category') ? array_filter(request()->get('category')) : [];
    $price_range = request()->has('price') ? request()->get('price') : null;
    $keyword = request()->has('keyword') ? request()->get('keyword') : null;
    $sort_by = request()->has('sort') ? request()->get('sort') : null;
    $type_id = request()->has('type') ? request()->get('type') : null;
    $area_id = session()->has('area_id') ? session('area_id') : null;

    $selected_filters = array(
        'cuisines'        => $this->itemRepo->getCuisinesByIds($cuisine_ids),
        'availabilities'  => $this->itemRepo->getAvailabilitiesByIds($availability_ids),
        'categories'      => $this->itemRepo->getCategories($category_ids),
    );

    $perPage = 9;

    $items = $this->itemRepo->searchMealsAndCombos($area_id, $cuisine_ids, $availability_ids, $category_ids, $price_range, $type_id, $keyword, $sort_by, $perPage);

    $items = [
        'items'       => array_slice($this->transformer->transformItems($items), $items->perPage()*($items->currentPage() - 1), $perPage),
        'pagination'  => [
            'total'         => $items->total(),
            'current_page'  => $items->currentPage(),
            'last_page'     => $items->lastPage(),
            'per_page'      => $items->perPage(),
            'next_page_url' => $items->nextPageUrl()
        ],
        'total'	 => $items->total(),
    ];


    $breadcrumbs = Breadcrumbs::render('xprs.search');

    if(request()->ajax()) return $items;

    return view('items.xprs_menu', compact('items', 'filters', 'selected_filters', 'area', 'breadcrumbs'));
  }

  /**
   * get all hot deals
   */
  public function getHotDeals()
  {
    $seo = $this->commonRepo->getSeoBySlug('todays-specials');

    if($seo) $this->setSeo($seo->meta_title, $seo->meta_description,  $seo->meta_image_url);

    $perPage = 12;

    $items = $this->itemRepo->getHotDeals($perPage);

    $items = [
        'items'       => array_slice($this->transformer->transformItems($items), $items->perPage()*($items->currentPage() - 1), $perPage),
        'pagination'  => $items ->appends(request()->except('page'))->render(),
        'total'	      => $items->total(),
    ];

    $area = $this->locationRepo->getSessionArea();

    if(request()->ajax()) return $items;

    $breadcrumbs = Breadcrumbs::render('hot_deals.search');

    return view('items.hot_deals', compact('items', 'area', 'breadcrumbs'));
  }

  public function getXprsMenuSingle($slug)
  {
    $item = $this->itemRepo->getSingleItem($slug);

    if(!$item) return view('errors.404');

    $this->setSeo($item->itemable->name, $item->itemable->description, $item->image_url );

    $breadcrumbs = Breadcrumbs::render('xprs.single', $item);

    return view('items.xprs_menu_single', compact('item', 'breadcrumbs'));
  }
  /**
   * @param $slug
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getHotDealsSingle($slug)
  {
    $item = $this->itemRepo->getSingleItem($slug);

    $this->setSeo($item->itemable->name, $item->itemable->description);

    $trending = $this->itemRepo->getTrendingItems(session('area_id'));

    $breadcrumbs = Breadcrumbs::render('hot_deals.single', $item);

    return view('items.hot_deals_single', compact('item', 'trending', 'breadcrumbs'));
  }

  /**
   * @return \Illuminate\Http\JsonResponse
   */
  public function postXprsMenuArea()
  {
    $inputs = request()->all();

    session(['area_id' => $inputs['area_id']]);

    $area = $this->locationRepo->getAreaById($inputs['area_id']);
  }

  /**
   * @return \Illuminate\Http\JsonResponse
   */
  public function postHotDealsArea()
  {
    $inputs = request()->all();

    session(['area_id' => $inputs['area_id']]);

    $area = $this->locationRepo->getAreaById($inputs['area_id']);

  }


}

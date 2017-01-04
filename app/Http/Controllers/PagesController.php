<?php
namespace App\Http\Controllers;

use Bueno\Exceptions\ModelNotFoundException;
use Bueno\Repositories\DbCouponRepository;
use Bueno\Repositories\DbUserRepository;
use Bueno\Validations\ContactFormValidator;
use SEOMeta;
use OpenGraph;
use Bueno\Repositories\DbLocationRepository;
use Bueno\Transformers\ItemTransformer;
use Flash;
use Bueno\Repositories\ItemRepository;
use Bueno\Services\VoiceOtpApi;
use Bueno\Repositories\DbCommonRepository;
use Bueno\Validations\CreateCaterLeadsValidator;
use Bueno\Validations\ValidationException;

class PagesController extends Controller {

  protected $commonRepo;

  protected $itemRepo;

  function __construct(
      DbCommonRepository $commonRepo,
      ItemRepository $itemRepo,
      CreateCaterLeadsValidator $caterValidator,
      DbLocationRepository $locationRepo,
      ItemTransformer $itemTransformer,
      ContactFormValidator $contactValidator,
      DbCouponRepository $couponRepo,
      DbUserRepository $userRepo
  )
  {
    $this->commonRepo = $commonRepo;

    $this->itemRepo = $itemRepo;

    $this->itemTransformer = $itemTransformer;

    $this->locationRepo = $locationRepo;

    $this->caterValidator = $caterValidator;

    $this->contactValidator = $contactValidator;

    $this->couponRepo = $couponRepo;

    $this->userRepo = $userRepo;
  }


  /**
   * shows the home page
   *
   * @return string
   */
  public function index()
  {

    $seo = $this->commonRepo->getSeoBySlug('home');

    if($seo) $this->setSeo($seo->meta_title, $seo->meta_description,  $seo->image_url);

    $testimonials = $this->commonRepo->getActiveTestimonials(5);

    $banners = $this->commonRepo->getBanners(5);

    $sliders = $this->commonRepo->getSliders(5);

    $ad_text = $this->commonRepo->getActiveAdText();

    $area = $this->locationRepo->getSessionArea();

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
        'items'       => array_slice($this->itemTransformer->transformItems($items), $items->perPage()*($items->currentPage() - 1), $perPage),
        'pagination'  => [
            'total'         => $items->total(),
            'current_page'  => $items->currentPage(),
            'last_page'     => $items->lastPage(),
            'per_page'      => $items->perPage(),
            'next_page_url' => $items->nextPageUrl()
        ],
        'total'	 => $items->total(),
    ];

    if(request()->ajax()) return $items;


    $offers = $this->couponRepo->getCouponOffers();

    return view('pages.index', compact('testimonials', 'banners', 'sliders', 'ad_text', 'filters', 'offers', 'area', 'items', 'filters', 'selected_filters'));
  }

  /**
   * shows catering page
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getCatering()
  {
    $seo = $this->commonRepo->getSeoBySlug('catering');

    if($seo) $this->setSeo($seo->meta_title, $seo->meta_description,  $seo->image_url);

    $trending = $this->itemRepo->getTrendingItems(8);

    return view('pages.catering', compact('trending'));
  }

  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getCateringQuery()
  {
    $seo = $this->commonRepo->getSeoBySlug('catering-query');

    if($seo) $this->setSeo($seo->meta_title, $seo->meta_description,  $seo->image_url);

    $trending = $this->itemRepo->getTrendingItems(8);

    return view('pages.catering_form', compact('trending'));
  }

  /**
   * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function postCateringQuery()
  {
    $inputs = request()->all();

    $trending = $this->itemRepo->getTrendingItems(8);

    try
    {
      $this->caterValidator->fire($inputs);
    }
    catch(ValidationException $e)
    {
      return redirect(route('pages.catering.query.get'))->withInput()->withErrors($e->getErrors());
    }

    $this->commonRepo->createCaterLead($inputs);

     $this->commonRepo->sendCateringMail($inputs);

    Flash::success('We got your query. We will contact you soon !');

    return view('pages.catering_thanks', compact('trending'));
  }

  public function getCateringDownload()
  {
    return response()->download(public_path() . '/downloads/catering.pdf');
  }
  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getFaq()
  {
    $seo = $this->commonRepo->getSeoBySlug('faq');

    if($seo) $this->setSeo($seo->meta_title, $seo->meta_description,  $seo->image_url);

    return view('pages.faq');
  }

  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getAbout()
  {
    $seo = $this->commonRepo->getSeoBySlug('about');

    if($seo) $this->setSeo($seo->meta_title, $seo->meta_description,  $seo->image_url);

    return view('pages.about');
  }

  public function getContact()
  {
    $seo = $this->commonRepo->getSeoBySlug('contact');

    if($seo) $this->setSeo($seo->meta_title, $seo->meta_description,  $seo->image_url);

    return view('pages.contact');
  }

  public function postContact()
  {
    $inputs = request()->all();

    try
    {
      $this->contactValidator->fire($inputs);
    }
    catch(ValidationException $e)
    {
      return redirect(route('pages.contact.get'))->withErrors($e->getErrors())->withInput();
    }

    $this->commonRepo->createContactQuery($inputs);
    $this->commonRepo->sendContactMail($inputs);

    Flash::success('We got your query ! We will contact you soon.');

    return redirect(route('pages.contact.get'));
  }

  public function getBusinessQuery()
  {
    $seo = $this->commonRepo->getSeoBySlug('business-query');

    if($seo) $this->setSeo($seo->meta_title, $seo->meta_description,  $seo->image_url);

    return view('pages.business_query');
  }

  public function postBusinessQuery()
  {
    $inputs = request()->all();
    try
    {
      $this->contactValidator->fire($inputs);
    }
    catch(ValidationException $e)
    {
      return redirect(route('pages.business.get'))->withErrors($e->getErrors())->withInput();
    }

    $this->commonRepo->saveBusinessQuery($inputs);
    $this->commonRepo->sendContactMail($inputs);

    Flash::success('We got your mail ! We will contact you soon.');

    return redirect(route('pages.business.get'));
  }

  public function getFeedback()
  {
    $seo = $this->commonRepo->getSeoBySlug('feedback');

    if($seo) $this->setSeo($seo->meta_title, $seo->meta_description,  $seo->image_url);

    return view('pages.feedback');
  }

  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getPress()
  {
    $seo = $this->commonRepo->getSeoBySlug('press');

    if($seo) $this->setSeo($seo->meta_title, $seo->meta_description,  $seo->image_url);

    $press = $this->commonRepo->getAllPress();

    return view('pages.press', compact('press'));
  }


  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getPrivacyPolicy()
  {
    $seo = $this->commonRepo->getSeoBySlug('privacy-policy');

    if($seo) $this->setSeo($seo->meta_title, $seo->meta_description,  $seo->image_url);

    return view('pages.privacy_policy');
  }

  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getTermsConditions()
  {
    $seo = $this->commonRepo->getSeoBySlug('terms-conditions');

    if($seo) $this->setSeo($seo->meta_title, $seo->meta_description,  $seo->image_url);

    return view('pages.terms_conditions');
  }

  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getRefundCancellation()
  {
    $seo = $this->commonRepo->getSeoBySlug('refund-cancellation');

    if($seo) $this->setSeo($seo->meta_title, $seo->meta_description,  $seo->image_url);

    return view('pages.refund_cancellation');
  }

  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getCateringEnquiry()
  {
    $seo = $this->commonRepo->getSeoBySlug('catering-enquiry');

    if($seo) $this->setSeo($seo->meta_title, $seo->meta_description,  $seo->image_url);

    return view('pages.catering_enquiry');
  }

  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getCorporateOrders()
  {
    $seo = $this->commonRepo->getSeoBySlug('corporate-orders');

    if($seo) $this->setSeo($seo->meta_title, $seo->meta_description,  $seo->image_url);

    return view('pages.corporate_orders');
  }

  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getCareers()
  {
    $seo = $this->commonRepo->getSeoBySlug('careers');

    if($seo) $this->setSeo($seo->meta_title, $seo->meta_description,  $seo->image_url);

    return view('pages.careers');
  }

  public function sitemap()
  {
    $items = $this->itemRepo->getAllDisplayableItems();

    $content = view('pages.sitemap', compact('items'));

    return response($content, '200')->header('Content-Type', 'text/xml');
  }

  public function getAreas()
  {
    $inputs = request()->all();

    $areas = $this->commonRepo->getAreas($inputs['q']);

    return response()->json($areas);
  }

  public function getOffers()
  {
    $coupons = $this->couponRepo->getCouponOffers();

    $area = $this->locationRepo->getSessionArea();

    return view('pages.offers', compact('coupons', 'area'));

  }

  public function getOptOutSMS()
  {
    return view('pages.opt_out');
  }

  public function postOptOutSMS()
  {
    $inputs = request()->all();

    try
    {
      $this->userRepo->optOutUserFromSMS($inputs['phone']);
    }
    catch(ModelNotFoundException $e)
    {
      Flash::danger('No User Found !');

      return redirect()->back();
    }

    return view('pages.opt_out_success');
  }

}
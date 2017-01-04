<?php

namespace App\Http\Controllers\Admin;

use Flash;
use App\Models\Item;
use App\Models\Goody;
use App\Http\Requests;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Bueno\Repositories\DbMealRepository as MealRepo;
use Bueno\Repositories\DbUserRepository as UserRepo;
use Bueno\Repositories\DbCouponRepository as CouponRepo;
use Bueno\Repositories\DbKitchenRepository as KitchenRepo;
use Bueno\Validations\ValidationException;
use Bueno\Repositories\DbLocationRepository as LocationRepo;
use Bueno\Repositories\DbOrderRepository as OrderRepo;
use Bueno\Validations\CreateCouponValidator as CouponValidator;



class CouponController extends Controller
{

    protected $kitchenRepo,$locationRepo,$mealRepo,$userRepo,$couponRepo,$couponValidator,$orderRepo;

    function __construct(KitchenRepo $kitchenRepo,LocationRepo $locationRepo,MealRepo $mealRepo,UserRepo $userRepo,CouponRepo $couponRepo,CouponValidator $couponValidator,OrderRepo $orderRepo)
    {
        ini_set('max_execution_time', 300);
         ini_set('memory_limit', '2048M');
        $this->locationRepo = $locationRepo;
        $this->kitchenRepo = $kitchenRepo;
        $this->mealRepo  = $mealRepo;
        $this->userRepo = $userRepo;
        $this->couponRepo = $couponRepo;
        $this->couponValidator = $couponValidator;
        $this->orderRepo = $orderRepo;
        $this->middleware('access.coupons');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inputs = request()->all();

        $coupons = $this->couponRepo->getAllNonReferralCoupons($inputs);
        $page = 'datatables';
        return view('admin.coupon.coupons',compact('page','coupons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $items = Item::with('itemable')->get();

        $kitchens = $this->kitchenRepo->getAllKitchens();

        $states = $this->locationRepo->getAllStates();

        $categories = $this->mealRepo->getAllCategories();

        $cuisines = $this->mealRepo->getAllCuisines();

        $users = $this->userRepo->getAllUsers();

        $payment_modes = $this->orderRepo->getAllPaymentModes();

        $meals = $this->mealRepo->getAllMeals();

        $combos = $this->mealRepo->getAllCombos();

        $goodies = Goody::get();

        $page = 'datatables';

        return view('admin.coupon.new_coupon',compact('page','meals','combos','states','kitchens','categories','cuisines','goodies','users','payment_modes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $inputs = $request->all();
        try
        {
          $this->couponValidator->fire($inputs);

          if(!$this->couponRepo->couponGives($inputs))
          {
            Flash::danger('Coupon does not contain any Gives');
            return redirect()->route('admin.new_coupon')->withInput();
          }

          $inputs = $this->couponRepo->transformCouponInputs($inputs);

        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.new_coupon'))->withErrors($e->getErrors())->withInput();
        }

       Flash::success('Coupon Successfully Created!');
        $this->couponRepo->newCoupon($inputs);

        return redirect()->route('admin.coupons');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $coupon = $this->couponRepo->getCouponById($id);

        if($coupon->type==1)
        {
          return redirect()->route('admin.coupons')->withInput();
        }

        $kitchens = $this->kitchenRepo->getAllKitchens();

        $states = $this->locationRepo->getAllStates();

        $categories = $this->mealRepo->getAllCategories();

        $cuisines = $this->mealRepo->getAllCuisines();

        $users = $this->userRepo->getAllUsers();

        $payment_modes = $this->orderRepo->getAllPaymentModes();

        $items = $this->mealRepo->getAllItems();

        $coupon_attributes = $this->couponRepo->getCouponAttributes($coupon);

        $all_items = $this->mealRepo->getAllItemsNoCondition();

        $page = 'datatables';

        return view('admin.coupon.update_coupon',compact('page','coupon','items','states','kitchens','categories','cuisines','goodies','users','payment_modes','coupon_attributes','all_items'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

      $inputs = $request->all();
    
      try
      {
        $this->couponValidator->fire($inputs);

//        if(!$this->couponRepo->couponGives($inputs))
//        {
//          Flash::danger('Coupon does not contain any Gives');
//          return redirect()->route('admin.update_coupon',$inputs['id'])->withInput();
//        }

        $inputs = $this->couponRepo->transformCouponInputs($inputs);

      }
      catch(ValidationException $e)
      {
        return redirect(route('admin.update_coupon',$inputs['id']))->withErrors($e->getErrors())->withInput();
      }

      $coupon = $this->couponRepo->updateCoupon($id,$inputs);

      Flash::success('Coupon Updated successfully');

      return redirect()->route('admin.coupons');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->couponRepo->deleteCouponById($id))
        {
          Flash::success('Coupon Successfully Deleted');
          return redirect()->route('admin.coupons');
        }
      else
      {
          Flash::danger('Coupon cannot be deleted.');
          return redirect()->route('admin.update_coupon',$id);
      }
    }

}

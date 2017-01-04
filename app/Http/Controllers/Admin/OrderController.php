<?php

namespace App\Http\Controllers\Admin;

use App\Events\OrderStatusWasUpdated;
use App\Events\OrderWasDispatched;
use App\Models\CancelReason;
use App\Models\Order;
use Bueno\Exceptions\CheckoutValidationException;
use Bueno\Exceptions\ModelNotFoundException;
use Flash;
use Excel;
use Datatable;
use DateTime;
use App\Http\Requests;
use App\Models\Group;
use App\Models\Setting;
use App\Models\Coupon;
use App\Models\LogOrder;
use Illuminate\Http\Request;
use App\Events\OrderWasCreated;
use App\Events\OrderWasCancelled;
use App\Events\UserWasCreated;
use App\Events\OrderWasSettled;
use App\Http\Controllers\Controller;
use Bueno\Validations\ValidationException;
use Bueno\Repositories\DbNgoRepository as NgoRepo;
use Bueno\Repositories\DbCommonRepository as CommonRepo;
use Bueno\Repositories\DbMealRepository as MealRepo;
use Bueno\Repositories\DbUserRepository as UserRepo;
use Bueno\Repositories\DbOrderRepository as OrderRepo;
use Bueno\Repositories\DbCouponRepository as CouponRepo;
use Bueno\Repositories\DbKitchenRepository as KitchenRepo;
use Bueno\Repositories\DbLocationRepository as LocationRepo;
use Bueno\Repositories\DbSettingRepository as SettingRepo;
use Bueno\Validations\CreateOrderValidator as OrderValidator;


class OrderController extends Controller
{

    protected $orderRepo,$locationRepo,$mealRepo,$userRepo,$orderValidator,$ngoRepo,$kitchenRepo,$couponRepo,$settingRepo,$commonRepo;

    function __construct(CommonRepo $commonRepo,OrderRepo $orderRepo,LocationRepo $locationRepo,MealRepo $mealRepo,UserRepo $userRepo,OrderValidator $orderValidator,NgoRepo $ngoRepo,KitchenRepo $kitchenRepo,CouponRepo $couponRepo,SettingRepo $settingRepo)
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '2048M');
        $this->orderRepo = $orderRepo;
        $this->locationRepo = $locationRepo;
        $this->mealRepo = $mealRepo;
        $this->userRepo = $userRepo;
        $this->orderValidator = $orderValidator;
        $this->ngoRepo = $ngoRepo;
        $this->couponRepo = $couponRepo;
        $this->kitchenRepo = $kitchenRepo;
        $this->settingRepo = $settingRepo;
        $this->commonRepo = $commonRepo;
        $this->middleware('access.order');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $user = auth()->user();
        $today = new DateTime();
        $date['end'] = $today->format('m/d/Y H:i:s');
        $start  = new DateTime('first day of this month');
        $date['start'] = $start->format('m/d/Y H:i:s');
        $inputs = request()->all();

        if($user->group->name=="Kitchen Manager")
        {
          if($user->manageKitchen)
            $kitchen_id = $user->manageKitchen->kitchen_id;
          else
            $kitchen_id = -1;

          $orders = $this->orderRepo->getOrdersListing($inputs,$kitchen_id);

        }
        else
        {
        $orders = $this->orderRepo->getOrdersListing($inputs);
        }
        $orderStatuses = $this->orderRepo->getAllStatuses();

        $page = 'dashboard';
        return view('admin.order.orders',compact('page','orders','date','orderStatuses'));
    }

    public function getMarkOrdersAsSettled()
    {
      $inputs = request()->all();

      if(!isset($inputs['ids']) || $inputs['ids'] == "")
      {
        Flash::danger('No Ids Selected');

        return redirect()->back();
      }
      $order_ids = explode(',', $inputs['ids']);

      $this->orderRepo->markOrdersAsSettled($order_ids);

      Flash::success('Orders Updated');

      return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {

          $this->commonRepo->checkIfKitchenIsOpen();

        }
        catch(CheckoutValidationException $e)
        {
          Flash::danger($e->getMessage());

          return redirect(route('admin.orders'));
        }

        $states = $this->locationRepo->getAllStates();
        $areas = $this->locationRepo->getAllActiveAreas();
        $kitchens = $this->kitchenRepo->getAllKitchens();
        $meals = $this->mealRepo->getAllSellableMeals();
        $payment_modes = $this->orderRepo->getAllPaymentModesCOD();
        $sources = $this->orderRepo->getAllOrderSources();
        $combos = $this->mealRepo->getAllSellableCombos();
        $statuses = $this->orderRepo->getAllStatuses();
        $ngos = $this->ngoRepo->getAllNgos();
        $items = $this->mealRepo->getAllSellableItems();
        $page = 'datatables';

        if($meals->count()==0 && $combos->count()==0)
        {
          Flash::info('Add a meal first in order to create an order !');
          return redirect()->route('admin.meals');
        }

        if($areas->count()==0)
        {
          Flash::info('You have to create an area first in order to create an order !');
          return redirect()->route('admin.areas');
        }

        return view('admin.order.quick',compact('page','states','meals','items','payment_modes','sources','combos','statuses','ngos','settings','kitchens'));
    }

  public function getNewOrders()
  {

    $user = auth()->user();

    if($user->group->name=="Kitchen Manager")
    {

      if($user->manageKitchen)
      $kitchen_id = $user->manageKitchen->kitchen_id;
      else
      $kitchen_id = -1;

      $orders = $this->orderRepo->getNewOrders($kitchen_id);
    }
    else
    {
      $orders = $this->orderRepo->getNewOrders();
    }

    $page = 'new-orders';

    return view('admin.order.new_orders', compact('orders', 'page'));
  }

  public function getPackedOrders()
  {

    $user = auth()->user();

    if($user->group->name=="Kitchen Manager")
    {
      if($user->manageKitchen)
      $kitchen_id = $user->manageKitchen->kitchen_id;
      else
      $kitchen_id = -1;

      $orders = $this->orderRepo->getPackedOrders($kitchen_id);
    }
    else
    {
      $orders = $this->orderRepo->getPackedOrders();
    }

    $page = 'packed-orders';

    return view('admin.order.packed_orders', compact('orders', 'page'));
  }

  public function getDispatchedOrders()
  {
    $user = auth()->user();

    if($user->group->name=="Kitchen Manager")
    {
      if($user->manageKitchen)
      $kitchen_id = $user->manageKitchen->kitchen_id;
      else
      $kitchen_id = -1;

      $orders = $this->orderRepo->getDispatchedOrders($kitchen_id);
    }
    else
    {
      $orders = $this->orderRepo->getDispatchedOrders();
    }
    
    $page = 'dispatched-orders';

    return view('admin.order.dispatched_orders', compact('orders', 'page'));
  }

  public function getCancelledOrders()
  {
    $user = auth()->user();

    $inputs = request()->all();

    if($user->group->name=="Kitchen Manager")
    {
      if($user->manageKitchen)
      $kitchen_id = $user->manageKitchen->kitchen_id;
      else
      $kitchen_id = -1;

      $orders = $this->orderRepo->getCancelledOrders($kitchen_id, $inputs);
    }
    else
    {
      $orders = $this->orderRepo->getCancelledOrders(null, $inputs);
    }
    
    $page = 'cancelled-orders';

    return view('admin.order.cancelled_orders_paginated', compact('orders', 'page'));
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

        try {
          $this->commonRepo->checkIfKitchenIsOpen();


        }
        catch(CheckoutValidationException $e)
        {
          Flash::danger($e->getMessage());
          return redirect(route('admin.orders'));
        }

        try
        {
          $this->orderValidator->fire($inputs);
          $meals = $request->get('meals');
          $combos = $request->get('combos');
          $area_id = $request->get('area_id');
          $order_amount = $this->orderRepo->calculateAmount($request);
          if($order_amount>=2500)
          {
            
          }

          $order =null;
          if($inputs['resource_order_no'])
           $order = Order::where("resource_order_no",$inputs['resource_order_no'])->where('source_id', $inputs['source_id'])->whereNotNull('resource_order_no')->first();
           if($order)
           {
             Flash::danger('Resource Order No With This Source Already Exist.');
            return redirect()->route('admin.new_order')->withInput();
           }
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.new_order'))->withErrors($e->getErrors())->withInput();
        }

        $user = $this->userRepo->getUserByPhone($inputs['phone']);


        if($user==null)
        {
            $group = Group::where('name',config('bueno.groups.2'))->first();

            if($group==null)
            {
              Flash::danger('Order Cannot be Placed . Create User Group');
              return redirect()->route('admin.groups')->withInput();
            }
            $request['group_id'] = $group->id;

            $user = $this->userRepo->newUser($request);

            event(new UserWasCreated($user));

        }

        //Non user cannot order

        else if($user->group->name!="User" || $user->status!=1)
        {
          Flash::danger('Order Cannot be Placed for a non User or Disabled User');
          return redirect()->route('admin.new_order')->withInput();
        }

        $address = $this->userRepo->getUserAddress($user->id,$request->get('address'));


        if($address==null)
        {   
            $area_id = $request->get('area_id');
            $new_address['address'] = $request->get('address');
            $new_address['user_id'] = $request->get('user_id');
            $new_address['address_name'] = $request->get('company_name');
            $area = $this->locationRepo->getAreaById($area_id);
            $new_address['area_id'] = $area->id;
            $new_address['pincode'] = $area->pincode;
            $address = $this->userRepo->newUserAddress($user->id,$new_address);
            $stockTest = $this->orderRepo->checkStock($meals,$combos,$area->id);
        }
      else
      {
        $stockTest = $this->orderRepo->checkStock($meals,$combos,$area_id);
      }

    // Stock Check

      if(!$stockTest)
      {
        Flash::danger('Meals Exceeds Stock Limit');
        return redirect()->route('admin.new_order')->withInput();
      }

    // Area Status
      if($address->area->status==0)
      {
        Flash::danger('This Area is Currently Disabled');
        return redirect()->route('admin.new_order')->withInput();
      }

    // Minimum Order Limit Check
      if($order_amount<$address->area->min_order_amount)
      {
        Flash::danger('Minimum Order Amount is'.$address->area->min_order_amount);
        return redirect()->route('admin.new_order')->withInput();
      }

        
        $order = $this->orderRepo->newOrder($user->id,$address->id,$request);

        event(new OrderWasCreated($order));

        $previous_status['order'] = 1;
        $previous_status['payment'] = 2;
        $current_status['order'] = $inputs['status'];
        $current_status['payment'] = 2;
        $comment = 'New Order initiated.';
        if(isset($inputs['comment']))
          $comment .= $inputs['comment'];
        if ($order->instruction!=null) {
        $comment .= "Instruction : " . $order->instruction;
        }
        $this->orderRepo->addOrderLog($order->id,$previous_status,$current_status,$comment);

        Flash::success('Order Created !');

        return redirect()->route('admin.orders');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        $user = auth()->user();
        if($user->group->name=="Kitchen Manager")
        { 
          $kitchen_id = $user->manageKitchen->kitchen_id;
          $kitchen = $this->kitchenRepo->getKitchenById($kitchen_id);
          $orders = $kitchen->orders;
          $order_ids = $orders->pluck('id')->toArray();
          if(!in_array($id, $order_ids))
          {
            return redirect()->route('admin.orders');
          }
        }
        
        $order = $this->orderRepo->getOrderById($id);
        
        $states = $this->locationRepo->getAllStates();
        
        $cities = $this->locationRepo->getAllCities();
        
        $areas = $this->locationRepo->getAllAreas();
        
        $meals = $this->mealRepo->getAllMeals();
        
        $payment_modes = $this->orderRepo->getAllPaymentModes();
        
        $sources = $this->orderRepo->getAllOrderSources();
        
        $combos = $this->mealRepo->getAllCombos();
        
        $statuses = $this->orderRepo->getAllStatuses();
        
        $ngos = $this->ngoRepo->getAllNgos();
        
        $order_items = $order->itemsInfo;
        
        $cancel_reasons = $this->orderRepo->getAllOrderCancelReasons();

        $payment_status = $this->orderRepo->getAllPaymentStatus();

        $item_count = 0;
        
        foreach($order_items as $oitem)
        {
          $item_count +=$oitem->quantity;
        }
        
        $orderLogs = LogOrder::where('order_id',$id)->get();

        $page = 'datatables';

        return view('admin.order.update_order',compact('page','payment_status','ngos','order','states','cities','areas','meals','payment_modes','sources','combos','statuses','orderLogs','delivery_boys','order_items','item_count','cancel_reasons'));
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
        $order = Order::find($id);

        $previous_ostatus = $order->status;
        $previous_pstatus = $order->paymentInfo->status;

        $order_status = $request->get('status');

        $payment_status = $request->get('payment_status');

        $cancel_reason_id = $request->get('cancel_reason_id');


      //Order Log
      $this->orderRepo->updateOrderLog($order->id,$request);

      $this->orderRepo->updatePaymentStatus($order->id,$request->get('payment_status'));

      if($order_status==6 && $order->status !=6)
      {
        $order->cancel_reason_id = $cancel_reason_id;

        $order->save();

        event(new OrderWasCancelled($order));

      }
      $order->status = $request->get('status');

      if(!($previous_pstatus==3 && $previous_ostatus==4))
      if($payment_status==3 && $order_status==4)
      {
        event(new OrderWasSettled($order));
      }

      $order->save();

      // Payment Status


        Flash::success('Order Updated');

        return redirect()->route('admin.orders');
    }

    public function applyCoupon()
    {   

        $inputs = request()->all();

        if($inputs['coupon_code']==null)
        {
          $data['message'] = 'No Coupon Entered';
          $data['code'] = '0' ;
          $data['message_type'] = 'info';
          $data['discount'] = '0';
        }
        else if(!isset($inputs['meals']) && !isset($inputs['combos']))
        {
        $data['message'] = 'Select at least one meal';
        $data['code'] = '1';
        $data['message_type'] = 'error';
          $data['discount'] = '0';
        }
        else if(($inputs['first_name']==null) || ($inputs['phone']==null))
        {
        $data['message'] = 'Fill complete user Details';
        $data['code'] = '1';
        $data['message_type'] = 'info';
          $data['discount'] = '0';
        }
        else if(($inputs['area_id']==null))
        {
        $data['message'] = 'Fill address details';
        $data['code'] = '1';
        $data['message_type'] = 'info';
          $data['discount'] = '0';
        }
        else
        {

          $coupon_response = $this->orderRepo->checkCoupon(request());

          return $coupon_response;
        }
        return $data;
    }

    public function getDispatch()
    {
      $page = 'dispatch';

      return view('admin.order.dispatch', compact('page'));
    }

    public function postDispatch()
    {
      $inputs = request()->all();

      try
      {
        $order = $this->orderRepo->dispatchOrder($inputs['order_no']);
      }
      catch(ModelNotFoundException $e)
      {
        Flash::danger($e->getMessage());

        return redirect()->back();
      }

      session()->flash('order_id', $order->id);

      Flash::success('Order with ID #' . $inputs['order_no'] . ' has been dispatched. <a target="_blank" href="'.route('admin.update_order',$order->id).'">Edit Order</a>');

      return redirect()->back();
    }

    public function getPack()
    {
      $page = 'pack';

      return view('admin.order.pack', compact('page'));
    }

    public function postPack()
    {
      $inputs = request()->all();

      try
      {
        $this->orderRepo->packOrder($inputs['order_no']);
      }
      catch(ModelNotFoundException $e)
      {
        Flash::danger($e->getMessage());

        return redirect()->back();
      }


      Flash::success('Order with ID #' . $inputs['order_no'] . ' has been packed');

      return redirect()->back();
    }

    public function getKot($order_id)
    {
      $order = Order::find($order_id);

      return view('admin.order.kot', compact('order'));
    }

    public function getInvoice($order_id)
    {
      $order = Order::find($order_id);

      $invoice_text = Setting::where('key','invoice_message')->first();

      return view('admin.order.invoice', compact('order','invoice_text'));
    }

  /**
   *  Exports the array passed to xls file.
   */
  public function export()
  {
    $orders = $this->orderRepo->getAllOrders();
    $inputs = request()->all();

    $start = new DateTime($inputs['start']);
      $end = new DateTime($inputs['end']);

    if(date_diff($start,$end)->d>15)
    {
      Flash::danger('Maximum Range that can be selected is 15 Days');
      return redirect(route('admin.orders'));
    }

    $orders = $orders->filter(function($order) use($start,$end){
      return $order->created_at>=$start && $order->created_at<=$end ? true : false;
    });

    $data = $this->orderRepo->transformForExcel($orders);

    Excel::create('Orders Data', function($excel) use($data) {

      $excel->sheet('Sheet 1', function($sheet) use($data) {

        $sheet->fromArray($data);

      });

    })->export('xls');
  }

  public function export2()
    {
      $inputs = request()->all();

        $start = new DateTime($inputs['start'].' 00:00:00');
        $end = new DateTime($inputs['end'].' 23:59:59');

        $csv = [
                'Sign up Date',
                'Name',
                'Email',
                'Mobile',
                'Source',
                'Tier',
                'No of Orders',
                // 'Orders value',
                // 'Avg. Order Value',
                'First Order Date',
                'Last Order Date',
                'Email Notification',
                'SMS Notification'];
        $fp = fopen(storage_path().'/dumps/user-export.csv', 'w');
        fputcsv($fp,$csv);
        Order::where('created_at','>=',$start)->where('created_at','<=',$end)->where('group_id',2)->chunk(500, function($orders) use($fp)
        {
            foreach ($orders as $order)
            {
              $user_orders = $user->orders->filter(function($order){
                return (in_array($order->status,[2,3,4,7]));
                });
              // $total_money_spent = $user->total_money_spent;
              $order_count = $user_orders->count();
              $first_order = $user->first_order;
              $last_order = $user->last_order;
             fputcsv($fp, [
              $user->created_at,
              $user->full_name,
              $user->email,
              $user->phone,
              $first_order ? $first_order->source->name : 'Web',
              $user->membership->name,
              $order_count,
              // $total_money_spent,
              // $order_count!=0 ? round($total_money_spent/$order_count,2) : 0,
              $first_order ? $first_order->created_at : 'NA',
              $last_order  ? $last_order->created_at : 'NA',
              $user->email_notify ? 'Yes' : 'No',
              $user->sms_notify ? 'Yes' : 'No'
              ]);
            }
        });

        fclose($fp);

         header('Content-Disposition: attachment; filename="user-export.csv"');
         header("Cache-control: private");
         header("Content-type: application/force-download");
         header("Content-transfer-encoding: binary\n");

         echo readfile(storage_path().'/dumps/user-export.csv');
         exit;
    }

    public function editCancelReason()
    {
      $inputs = request()->all();

      $cancel_reason = $this->orderRepo->updateCancelReason($inputs);

      return $cancel_reason;
    }

    public function createCancelReason()
    {
      $inputs = request()->all();

      $cancel_reason = $this->orderRepo->createCancelReason($inputs);

      return $cancel_reason;
    }

}

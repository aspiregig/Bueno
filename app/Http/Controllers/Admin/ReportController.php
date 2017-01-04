<?php

namespace App\Http\Controllers\Admin;

use Bueno\Repositories\DbCommonRepository;
use Bueno\Repositories\DbKitchenRepository;
use Bueno\Repositories\DbLocationRepository;
use Bueno\Repositories\ItemRepository;
use Excel;
use Cache;
use Flash;
use DateTime;
use DateInterval;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kitchen;
use Bueno\Repositories\DbMealRepository as MealRepo;
use Bueno\Repositories\DbUserRepository as UserRepo;
use Bueno\Repositories\DbOrderRepository as OrderRepo;

class ReportController extends Controller
{
     protected $mealRepo,$userRepo,$orderRepo;

    function __construct(MealRepo $mealRepo,UserRepo $userRepo,OrderRepo $orderRepo, DbKitchenRepository $kitchenRepo, DbCommonRepository $commonRepo, DbLocationRepository $locationRepo, ItemRepository $itemRepo)
    {
      ini_set('max_execution_time', 600);
      ini_set('memory_limit', '2048M');
        $this->mealRepo = $mealRepo;
        $this->userRepo = $userRepo;
        $this->orderRepo = $orderRepo;
        $this->kitchenRepo = $kitchenRepo;
        $this->locationRepo = $locationRepo;
        $this->commonRepo = $commonRepo;
        $this->itemRepo = $itemRepo;
        $this->middleware('access.report');
    }
    /**
     * Display stock.
     *
     * @return \Illuminate\View\View
     */
    public function stock()
    {
        $meals = $this->mealRepo->getAllMeals();
        $page = 'datatables';
        return view('admin.reports.stock',compact('page','meals'));
    }
    /**
     *  Exports the array passed to xls file.
     */
    public function downloadStockReport()
    {
        $meals = $this->mealRepo->getAllMeals();

        $data = $this->mealRepo->transformForExcel($meals);

        Excel::create('Stock Report '.date('d M Y ',time()), function($excel) use($data) {

            $excel->sheet(date('d M Y ',time()), function($sheet) use($data) {

                $sheet->fromArray($data);
            });

        })->export('xls');
    }

  /**
   *  Daily Reports.
   */
  public function daily()
  {
    $page = 'datatables';
    return view('admin.reports.daily',compact('page','items'));
  }

  public function displayReport()
  {
    $page = 'dashboard';

    $minutes = 60;

    $current_hour = intval(date("H"));
    if(request()->get('start'))
    $start = new DateTime(request()->get('start'));
    else {
        $start = new DateTime('08:00:00');
        if($current_hour<9)
            $start->sub(new DateInterval('P1D'));
    }

    if(request()->get('end'))
    $end = new DateTime(request()->get('end'));
    else {
        $end = new DateTime('05:00:00');
        if($current_hour>=9)
            $end->add(new DateInterval('P1D'));
    }

    if(date_diff($start,$end)->d>15 || date_diff($start,$end)->m>0 || date_diff($start,$end)->y>0)
    {
      Flash::danger('Maximum Range that can be selected is 15 Days');
      return redirect(route('admin.report.daily'))->withInput();;
    }

    $sources = $this->orderRepo->getAllOrderSources();

    $stats['kitchen_id'] = -1;

    if(request()->get('kitchen_id') && request()->get('kitchen_id')!=-1)
    {
      $kitchen = $this->kitchenRepo->getKitchenById(request()->get('kitchen_id'));
      $kitchen_id = $kitchen->id;
    $stats['kitchen_id'] = $kitchen->id;

    }
    else
    {
      $kitchen_id = -1;
    }

    $stats['kitchens'] = $this->kitchenRepo->getAllKitchens();


    if($kitchen_id==-1)
    $kitchens = $stats['kitchens'];
    else 
    $kitchens = Kitchen::where('id',$kitchen_id)->get();

    $areas = $this->locationRepo->getAllAreas();

//     foreach ($areas as $area) {
//      $stats['area'][$area->id]['count'] = 0;
//      $stats['area'][$area->id]['cost'] = 0 ;
//      }

    if($kitchen_id!=-1)
    $areas = $kitchen->areas;
   
    if($kitchen_id==-1)
    $orders = $this->orderRepo->getAllOrdersByDates($start,$end);
    else
    $orders = $this->orderRepo->getAllOrdersByDates($start,$end,$kitchen_id);

  $items  = $this->itemRepo->getAllItems();


      $stats['time']['midnight_count'] = 0;
      $stats['time']['midnight_cost'] = 0;
      $stats['time']['breakfast_count'] = 0;
      $stats['time']['breakfast_cost'] = 0;
      $stats['time']['lunch_count'] = 0;
      $stats['time']['lunch_cost'] = 0;
      $stats['time']['snacks_count'] = 0;
      $stats['time']['snacks_cost'] = 0;
      $stats['time']['dinner_count'] = 0;
      $stats['time']['dinner_cost'] = 0;
      $stats['source']['own_count'] = 0;
      $stats['source']['own_cost'] = 0;
      $stats['source']['out_count'] = 0;
      $stats['source']['out_cost'] = 0;
      $stats['cod_sales'] = 0;
      $stats['cod_sales_with_tax'] = 0;
      $stats['cod_count'] = 0;
      $stats['online_sales'] = 0;
      $stats['online_count'] = 0;
      $stats['total_food_cost'] = 0;
      $stats['total_food_and_packaging_cost'] = 0;
      $stats['total_sales'] = 0;
      $stats['total_order_time'] = 0;
      $stats['total_rider_time'] = 0;
      $stats['total_customer_distance'] = 0;
      $stats['total_orders'] = 0;
      $stats['total_discount'] = 0;
      $stats['total_credits_used'] = 0;
      $stats['total_credits_awarded'] = 0;
      $stats['total_tax'] = 0;
      $stats['total_aov'] = 0;
      $stats['total_revenue'] = 0;
      $stats['total_items'] = 0;
      $stats['all_orders'] = 0;
      $stats['settled_orders'] = 0;
      $stats['delayed_orders'] = 0;
      $stats['cancelled_orders'] = 0;
      $stats['dispatched_orders'] = 0;
      $stats['initiated_orders'] = 0;
      $stats['kitchen_orders'] = 0;
      $stats['unsettled_orders'] = 0;
      $stats['pending_orders'] = 0;
      $stats['packed_orders'] = 0;
      $stats['one_item']['count']=0;
      $stats['one_item']['cost'] =0;
      $stats['more_item']['count']=0;
      $stats['more_item']['cost'] =0;
      $stats['new_users']['count']=0;
      $stats['new_users']['cost']=0;
      $stats['new_users']['cod']=0;
      $stats['new_users']['online']=0;
      $stats['old_users']['count']=0;
      $stats['old_users']['cost']=0;
      $stats['old_users']['cod']=0;
      $stats['old_users']['online']=0;
      $stats['feedback']['happy']=0;
      $stats['feedback']['sad']=0;
      foreach ($sources as $source) {
      $stats['source'][$source->id]['count'] = 0;
      $stats['source'][$source->id]['cost'] = 0 ;
      }

      foreach ($kitchens as $kitchen) {
      $stats['kitchen'][$kitchen->id]['count'] = 0;
      $stats['kitchen'][$kitchen->id]['cost'] = 0 ;
      $stats['kitchen'][$kitchen->id]['cost_post'] = 0 ;
      $stats['kitchen'][$kitchen->id]['cod'] = 0 ;
      $stats['kitchen'][$kitchen->id]['cod_amount'] = 0 ;
      $stats['kitchen'][$kitchen->id]['cod_amount_post_tax'] = 0 ;
      $stats['kitchen'][$kitchen->id]['online'] = 0 ;
      $stats['kitchen'][$kitchen->id]['online_amount'] = 0 ;
      $stats['kitchen'][$kitchen->id]['cancelled_orders'] = 0 ;
      }
//      foreach ($items as $item) {
//      $stats['item'][$item->id]['count'] = 0;
//      $stats['item'][$item->id]['cost'] = 0;
//      }

    foreach ($orders as $order) {
        $stats['all_orders']+=1;

      if($order->status != config('bueno.order_status.delivered') || $order->paymentInfo->status !=3)
        {
          if($order->status == config('bueno.order_status.cancelled'))
          {
            $stats['cancelled_orders']+=1;
            $stats['kitchen'][$order->kitchen_id]['cancelled_orders']+=1;
          }
          elseif($order->status == config('bueno.order_status.dispatched'))
              $stats['dispatched_orders']+=1;
          elseif($order->status == config('bueno.order_status.in_kitchen'))
              $stats['kitchen_orders']+=1;
          elseif($order->status == config('bueno.order_status.initiated'))
              $stats['initiated_orders']+=1;
          elseif($order->status == config('bueno.order_status.pending'))
              $stats['pending_orders']+=1;
          elseif($order->status == config('bueno.order_status.packed'))
              $stats['packed_orders']+=1;
          elseif($order->status == config('bueno.order_status.delivered') && $order->paymentInfo->status !=3)
            $stats['unsettled_orders']+=1;
         continue;
        }
      $stats['settled_orders']+=1;

      $amount_post = $order->paymentInfo ? $order->paymentInfo->amount : 0;

      $tax = $order->invoice->sum(function($charge)
        {
          return in_array($charge->charge_for, ['Service Tax','VAT','Delivery Charge','Packaging Charge','Service Charge']) ? $charge->amount : 0;
        });

      $actual_order_amount = $order->invoice->where('charge_for','Order Amount')->first();

      $actual_order_amount = $actual_order_amount ? $actual_order_amount->amount : 0;

      $discount = $order->invoice->where('charge_for','Discount')->first();

      $discount = $discount ? $discount->amount : 0;

      $credits_used =  $order->invoice->where('charge_for','Points Redeemed')->first();
      $credits_used = $credits_used ? $credits_used->amount : 0;

      $stats['total_sales'] +=$amount_post;
      $stats['total_order_time'] += $order-> total_delivery_time ? $order->total_delivery_time : 0;
      $stats['total_rider_time'] += $order-> rider_delivery_time ? $order->rider_delivery_time : 0;
      $stats['total_customer_distance'] += $order-> computed_travel_distance ? $order->computed_travel_distance : ($order-> travel_distance ? $order->travel_distance:0);
      if($order->total_delivery_time && $order->total_delivery_time>60)
          $stats['delayed_orders'] +=1;

      //Pre Tax

      $amount = $amount_post - $tax;

      if(in_array($order->source_id, array_values(config('bueno.source'))))
        $stats['total_discount'] +=$discount;

      $stats['total_credits_used'] +=$credits_used;
      $stats['total_tax'] +=$tax;
      $stats['total_orders'] += 1;

      if($order->user->created_at>=$start && $order->user->created_at<=$end)
      {
      $stats['new_users']['count']+=1;
      $stats['new_users']['cost']+=$amount;
      if($order->payment_mode_id==2)
      $stats['new_users']['cod']+=1;
      else
      $stats['new_users']['online']+=1;
      }
      else
      {
      $stats['old_users']['count']+=1;
      $stats['old_users']['cost']+=$amount;
      if($order->payment_mode_id==2)
      $stats['old_users']['cod']+=1;
      else
      $stats['old_users']['online']+=1;
      }


      if((Integer)$order->created_at->format('Hi')  < 600)
      {
        $stats['time']['midnight_count']+=1;
        $stats['time']['midnight_cost']+=$amount;
      }
      else if((Integer)$order->created_at->format('Hi')  < 1200)
      {
        $stats['time']['breakfast_count']+=1;
        $stats['time']['breakfast_cost']+=$amount;
      }
      else if((Integer)$order->created_at->format('Hi')  < 1600)
      {
        $stats['time']['lunch_count']+=1;
        $stats['time']['lunch_cost']+=$amount;
      }
      else if((Integer)$order->created_at->format('Hi')  < 2000)
      {
        $stats['time']['snacks_count']+=1;
        $stats['time']['snacks_cost']+=$amount;
      }
      else
      {
        $stats['time']['dinner_count']+=1;
        $stats['time']['dinner_cost']+=$amount;
      }

      if(in_array($order->status,[1,2,4,7]))
      {
        $stats['source']['own_count'] += 1;
        $stats['source']['own_cost'] = $amount;
      }
      else
      {
      $stats['source']['out_count'] += 1;
      $stats['source']['out_cost'] = $amount;
      }

      if(in_array($order->payment_mode_id,[2]))
      {
        $stats['cod_count'] += 1;
        $stats['cod_sales'] += $amount;
        $stats['cod_sales_with_tax'] += $amount_post;
      }
      else
      {
      $stats['online_count'] += 1;
      $stats['online_sales'] += $amount;
      }

      $stats['source'][$order->source_id]['count']+=1;
      $stats['source'][$order->source_id]['cost']+=$amount;

      if(!isset($stats['area']) || !isset($stats['area'][$order->delivery_area_id]) || !isset($stats['area'][$order->delivery_area_id]['count']))
          $stats['area'][$order->delivery_area_id]['count'] = 0;
      if(!isset($stats['area'][$order->delivery_area_id]['cost']))
          $stats['area'][$order->delivery_area_id]['cost']=0;
      $stats['area'][$order->delivery_area_id]['count']+=1;
      $stats['area'][$order->delivery_area_id]['cost']+=$amount;

        if(!in_array($order->source_id, array_values(config('bueno.source'))))
            $coupon_code = $order->source->name;
        elseif ($order->coupon)
            $coupon_code = $order->coupon->code;
        else
            $coupon_code = "NO_COUPON";
        if (!isset($stats['coupon']) || !isset($stats['coupon'][$coupon_code]) || !isset($stats['coupon'][$coupon_code]['count']))
            $stats['coupon'][$coupon_code]['count'] = 0;
        if (!isset($stats['coupon'][$coupon_code]['discount']))
            $stats['coupon'][$coupon_code]['discount'] = 0;
        if (!isset($stats['coupon'][$coupon_code]['credits_awarded']))
            $stats['coupon'][$coupon_code]['credits_awarded'] = 0;
        if (!isset($stats['coupon'][$coupon_code]['cost']))
            $stats['coupon'][$coupon_code]['cost'] = 0;
        $stats['coupon'][$coupon_code]['count'] += 1;
        if(in_array($order->source_id, array_values(config('bueno.source'))))
            $stats['coupon'][$coupon_code]['discount'] += $discount;
        $stats['coupon'][$coupon_code]['cost'] += $amount;
        $stats['coupon'][$coupon_code]['credits_awarded'] += $order->paymentInfo ? $order->paymentInfo->cashback_credited : 0;
        $stats['total_credits_awarded']+= $order->paymentInfo ? $order->paymentInfo->cashback_credited : 0;


        $payment_mode = $order->paymentMode->name;
        if(!in_array($order->source_id, array_values(config('bueno.source'))))
          $payment_mode = $order->source->name."-".$order->paymentMode->name;
        if (!isset($stats['paymodes']) || !isset($stats['paymodes'][$payment_mode]) || !isset($stats['paymodes'][$payment_mode]['count']))
            $stats['paymodes'][$payment_mode]['count'] = 0;
        if (!isset($stats['paymodes'][$payment_mode]['cost']))
            $stats['paymodes'][$payment_mode]['cost'] = 0;
        $stats['paymodes'][$payment_mode]['count'] += 1;
        $stats['paymodes'][$payment_mode]['cost'] += $amount_post;

      if($order->deliveryBoy){
        if(!isset($stats['riders']) || !isset($stats['riders'][$order->deliveryBoy->full_name]) || !isset($stats['riders'][$order->deliveryBoy->full_name]['count']))
            $stats['riders'][$order->deliveryBoy->full_name]['count'] = 0;
        if(!isset($stats['riders'][$order->deliveryBoy->full_name]['total_time']))
            $stats['riders'][$order->deliveryBoy->full_name]['total_time']=0;
        if(!isset($stats['riders'][$order->deliveryBoy->full_name]['total_cod']))
          $stats['riders'][$order->deliveryBoy->full_name]['total_cod']=0;
        if(!isset($stats['riders'][$order->deliveryBoy->full_name]['delayed_count']))
              $stats['riders'][$order->deliveryBoy->full_name]['delayed_count']=0;
        $stats['riders'][$order->deliveryBoy->full_name]['count']+=1;
        $stats['riders'][$order->deliveryBoy->full_name]['total_time']+=$order-> rider_delivery_time ? $order->rider_delivery_time : 0;
        $stats['riders'][$order->deliveryBoy->full_name]['total_cod']+=$order->payment_mode_id==2 ? $amount_post : 0;
        if($order->rider_delivery_time && $order->rider_delivery_time>45)
            $stats['riders'][$order->deliveryBoy->full_name]['delayed_count'] +=1;
      }

        if($order->feedback_on_missed_call) {
            if($order->feedback_on_missed_call=='happy')
                $stats['feedback']['happy'] += 1;
            if($order->feedback_on_missed_call=='sad')
                $stats['feedback']['sad'] += 1;
        }

      $stats['kitchen'][$order->kitchen_id]['count']+=1;
      $stats['kitchen'][$order->kitchen_id]['cost']+=$amount;
      $stats['kitchen'][$order->kitchen_id]['cost_post']+=$amount_post;
      $stats['kitchen'][$order->kitchen_id]['cod']+=$order->payment_mode_id==2 ? 1 : 0;
      $stats['kitchen'][$order->kitchen_id]['cod_amount']+=$order->payment_mode_id==2 ? $amount : 0;
      $stats['kitchen'][$order->kitchen_id]['cod_amount_post_tax']+=$order->payment_mode_id==2 ? $amount_post : 0;
      $stats['kitchen'][$order->kitchen_id]['online']+=$order->payment_mode_id==2 ? 0 : 1;
      $stats['kitchen'][$order->kitchen_id]['online_amount']+=$order->payment_mode_id==2 ? 0 : $amount;

      if($order->items->count()==1)
      {
        $stats['one_item']['count'] +=1;
        $stats['one_item']['cost'] +=$amount;
      }
      else
      {
        $stats['more_item']['count'] +=1;
        $stats['more_item']['cost'] +=$amount;
      }

      foreach ($order->items as $item) {
        $stats['total_items'] +=1;
        if(!isset($stats['item']) || !isset($stats['item'][$item->id]) || !isset($stats['item'][$item->id]['count']))
          $stats['item'][$item->id]['count']=0;
        if(!isset($stats['item'][$item->id]['cost']))
          $stats['item'][$item->id]['cost']=0;
        if(!isset($stats['item'][$item->id]['food_cost']))
          $stats['item'][$item->id]['food_cost']=0;
        if(!isset($stats['item'][$item->id]['total_cost']))
              $stats['item'][$item->id]['total_cost']=0;
        $stats['item'][$item->id]['count']+=$item->pivot->quantity;
        $stats['item'][$item->id]['cost']+=($item->pivot->unit_price * $item->pivot->quantity);
        $stats['item'][$item->id]['food_cost']+=($item->food_cost * $item->pivot->quantity);
        $stats['item'][$item->id]['total_cost']+=($item->total_cost * $item->pivot->quantity);
          $stats['total_food_cost'] +=($item->food_cost * $item->pivot->quantity);
          $stats['total_food_and_packaging_cost'] +=($item->total_cost * $item->pivot->quantity);
      }

    }
    $stats['total_revenue'] = round($stats['total_sales'] - $stats['total_tax'],2);

    $stats['total_aov']= $stats['total_revenue'] ? round($stats['total_revenue']/$stats['total_orders'],2) : 0;
    $stats['avg_order_time']= $stats['total_order_time'] ? round($stats['total_order_time']/$stats['total_orders'],2) : 0;
    $stats['avg_rider_time']= $stats['total_rider_time'] ? round($stats['total_rider_time']/$stats['total_orders'],2) : 0;
    $stats['avg_customer_distance']= $stats['total_customer_distance'] ? round($stats['total_customer_distance']/($stats['total_orders']*1000),2) : 0;
    $stats['avg_kitchen_time']= $stats['avg_order_time']-$stats['avg_rider_time'];

    $stats['new_users']['aov'] = $stats['new_users']['count']==0 ? 0 : round(($stats['new_users']['cost'] / $stats['new_users']['count']), 2);
    $stats['new_users']['percent'] = $stats['total_revenue']==0 ? 0 : round((($stats['new_users']['cost']*100) / $stats['total_revenue']), 2);

    $stats['old_users']['aov'] = $stats['old_users']['count']==0 ? 0 : round(($stats['old_users']['cost'] / $stats['old_users']['count']), 2);
    $stats['old_users']['percent'] = $stats['total_revenue']==0 ? 0 : round((($stats['old_users']['cost']*100) / $stats['total_revenue']), 2);

    if(request()->get('download'))
     Excel::create('Report - '.$start->format('d M Y').' - '.$end->format('d M Y'), function($excel) use($stats,$orders,$start,$end,$sources,$areas,$items,$kitchens) {

      $excel->sheet('Sheet 1', function($sheet) use($stats,$orders,$start,$end,$sources,$areas,$items,$kitchens) {

        $sheet->loadView('reports.report_download', [
            'page' => 'dashboard',
            'stats' => $stats,
            'orders' => $orders,
            'start' => $start,
            'end' => $end,
            'sources'  => $sources,
            'areas'    => $areas,
            'items'   => $items,
            'kitchens'   => $kitchens,
        ]);
      });

    })->export('xls');
    return view('reports.daily_reports',compact('page','stats','orders','start','end','sources','areas','items','kitchens'));
  }

  public function dailyDownload(Request $request)
  {
    $data['start'] = new DateTime($request->get('start').' 00:00:00');
    $data['end'] = new DateTime($request->get('end').' 23:59:59');
    $data['todays_orders'] = $this->orderRepo->getAllOrdersByDates($data['start'], $data['end']);
    $data['kitchens'] = $this->kitchenRepo->getAllKitchens();
    $data['sources'] = $this->commonRepo->getAllOrderSources();
    $data['areas'] = $this->locationRepo->getAllAreas();
    $data['items']  = $this->itemRepo->getAllItems();

    Excel::create('Report', function($excel) use($data) {

      $excel->sheet('Sheet 1', function($sheet) use($data) {

        $sheet->loadView('reports.daily_reports', [
            'page' => 'dashboard',
            'stats' => $stats,
            'orders' => $orders,
            'start' => $start,
            'end' => $end,
            'sources'  => $sources,
            'areas'    => $areas,
            'items'   => $items,
            'kitchens'   => $kitchens,
        ]);
      });

    })->export('xls');
  }

    /**
     *  Exports the array passed to xls file.
     */
    public function downloadCustomerReport()
    {
        $users = $this->userRepo->getAllUsers();
        
        $data = $this->userRepo->transformForExcel($users);

        Excel::create('Customer Report '.date('d M Y ',time()), function($excel) use($data) {

            $excel->sheet(date('d M Y ',time()), function($sheet) use($data) {

                $sheet->fromArray($data);

            });

        })->export('xls');
    }
    
    /**
     *  Exports the array passed to xls file.
     */
    public function downloadPromoReport()
    {
        $orders = $this->orderRepo->getOrdersWhereCouponApplied();
        $data = $this->orderRepo->transformForExcel($orders);

        Excel::create('Order Report '.date('d M Y ',time()), function($excel) use($data) {

            $excel->sheet(date('d M Y ',time()), function($sheet) use($data) {

                $sheet->fromArray($data);

            });

        })->export('xls');
    }



    public function promo()
    {
        
    }

    public function gm()
    {
        $meals = $this->mealRepo->getAllMeals();
        $page = 'datatables';
        return view('admin.reports.gm',compact('page','meals'));
    }

    public function downloadGmReport()
    {
        $meals = $this->mealRepo->getAllMeals();

        $data = $this->mealRepo->transformForExcelGM($meals);

        Excel::create('GM Report '.date('d M Y ',time()), function($excel) use($data) {

            $excel->sheet(date('d M Y ',time()), function($sheet) use($data) {

                $sheet->fromArray($data);

            });

        })->export('xls');
    }

    public function downloadMulticuisineReport(Request $request)
    {
      $data['start'] = new DateTime($request->get('start').' 00:00:00');
      $data['end'] = new DateTime($request->get('end').' 23:59:59');
      $monthlyOrders = $this->orderRepo->getMonthlyOrders($data['start'],$data['end']);
    }
}

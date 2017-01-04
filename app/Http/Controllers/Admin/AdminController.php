<?php

namespace App\Http\Controllers\Admin;

use DateTime;
use Cache;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Bueno\Repositories\DbUserRepository as UserRepo;
use Bueno\Repositories\DbOrderRepository as OrderRepo;
use Bueno\Repositories\DbKitchenRepository as KitchenRepo;
use Bueno\Repositories\DbLocationRepository as LocationRepo;
use Bueno\Repositories\ItemRepository as ItemRepo;
class AdminController extends Controller
{

    protected $userRepo,$orderRepo,$locationRepo;

    function __construct(UserRepo $userRepo,ItemRepo $itemRepo,OrderRepo $orderRepo,LocationRepo $locationRepo,KitchenRepo $kitchenRepo)
    {
         ini_set('max_execution_time', 300);
         ini_set('memory_limit', '2048M');
        $this->userRepo = $userRepo;
        $this->orderRepo = $orderRepo;
        $this->locationRepo = $locationRepo;
        $this->kitchenRepo = $kitchenRepo;
        $this->itemRepo = $itemRepo;
        $this->middleware('access.dashboard',['except'=> ['home']]);
    }

    public function home()
    {
        $page = 'dashboard';

        if(auth()->guest())
            return redirect(route('pages.index'));

         if(auth()->user()->group->name=='User')
            return redirect(route('pages.index'));

      return view('admin.home',compact('page'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 

        if(auth()->guest())
            return redirect(route('pages.index'));

         if(auth()->user()->group->name=='User')
            return redirect(route('pages.index'));

        if(auth()->user()->group->name=='Kitchen Manager')
        {
          if(auth()->user()->kitchen->count()==0)
            return redirect(route('pages.index'));
          
          return $this->kitchenmanagerDashboard(auth()->user());
        }

        $minutes=1;

        $today = new DateTime();
        $stats['end'] = $today->format('m/d/Y');
        $start  = new DateTime('first day of this month');
        $start->modify('00:00:00');
        $stats['start'] = $start->format('m/d/Y');
        $stats['total_orders'] = Cache::remember('total_orders', $minutes, function() { return $this->orderRepo->getTotalOrders()->total();});
        $stats['total_users'] = Cache::remember('total_users', $minutes, function() { return $this->userRepo->getTotalUsers()->total();});
        $stats['total_sales'] = Cache::remember('total_sales', $minutes, function() {return $this->orderRepo->getTotalSales();});
        $stats['total_sales_7'] = Cache::remember('total_sales_7', $minutes, function() {return $this->orderRepo->getTotalSalesByDays('7');});
        $stats['total_sales_month_0'] = Cache::remember('total_sales_month_0', $minutes, function() use($start,$today){return $this->orderRepo->getTotalSalesByDates($start,$today);});
        $stats['total_sales_1'] = Cache::remember('total_sales_1', $minutes, function() {return $this->orderRepo->getTotalSalesByDays('0');});
        $stats['total_cities'] = Cache::remember('total_cities', $minutes, function() {return $this->locationRepo->getAllCities()->count();});
        $stats['kitchens'] = Cache::remember('kitchens', $minutes, function() {return $this->kitchenRepo->getAllKitchens();});
        $stats['sources'] = Cache::remember('sources', $minutes, function() {return $this->orderRepo->getAllOrderSources();});
        $stats['total_orders_1'] = Cache::remember('total_orders_1', $minutes, function() {return $this->orderRepo->getAllOrdersByDays('0')->count();});
        $stats['total_orders_7'] = Cache::remember('total_orders_7', $minutes, function() {return $this->orderRepo->getAllOrdersByDays('7')->count();});
        $stats['total_orders_month_0'] = Cache::remember('total_orders_month_0', $minutes, function() use($start,$today){return $this->orderRepo->getTotalOrdersByDates($start,$today)->count();});
        $stats['total_users_month_0'] = Cache::remember('total_users_month_0', $minutes, function() {return $this->userRepo->getAllUsersByMonths()->count();});
        $stats['last_30_days_order'] = Cache::remember('last_30_days_order', $minutes, function() {return $this->orderRepo->getLastDaysOrder('30');});
        $stats['last_16_days_sales'] = Cache::remember('last_16_days_sales', $minutes, function() {return $this->orderRepo->getLastDaysSales('16');});
        $stats['top_5_items'] = Cache::remember('top_5_items', $minutes, function() { return $this->itemRepo->getTrendingItems();});
        $stats['bottom_5_items'] = Cache::remember('bottom_5_items', $minutes, function() { return $this->itemRepo->getDeTrendingItems();});

//        $start['total_in_kitchen'] = Cache::remember('total_in_kitchen',$minutes,function(){ return $this->orderRepo->getPackedOrders()->count(); });
//        $start['total_in_dispatched'] = Cache::remember('total_in_dispatched',$minutes,function(){ return $this->orderRepo->getPackedOrders()->count(); });
//        $start['total_in_delivered'] = Cache::remember('total_in_delivered',$minutes,function(){ return $this->orderRepo->getPackedOrders()->count(); });
//        $start['total_in_cancelled'] = Cache::remember('total_in_cancelled',$minutes,function(){ return $this->orderRepo->getPackedOrders()->count(); });
        $key = "kitchen";
        $kitchen_count = 0;
        foreach ($stats['kitchens'] as $kitchen) {
           $kitchen_key = $key.$kitchen_count;
           $kitchen_count++;
           $stats[$kitchen_key]  = Cache::remember($kitchen_key, $minutes, function() use($kitchen){return $this->orderRepo->getSalesByKitchenId('15',$kitchen->id);});
        }
        $page = 'dashboard';
        return view('admin.index',compact('page','stats'));
    }

    public function kitchenmanagerDashboard($user)
    {   
        $kitchen = $this->kitchenRepo->getKitchenById($user->manageKitchen->kitchen_id);

        $end  = new DateTime();

        $start = new DateTime();

        $start->modify('last day of previous month');
        
        $page = 'dashboard';

        $stats = $this->dashboardStats($kitchen->id,$start,$end);

        return view('admin.kitchen_dashboard',compact('page','stats'));


    }

    public function dashboard(Request $request)
    {
        if(auth()->guest())
            return redirect('/');

         if(auth()->user()->group->name=='User')
            return redirect('/');

        
        $kitchen_id = $request->get('kitchen_id');

        $start = new DateTime($request->get('start')." 00:00:00");
        $end = new DateTime($request->get('end')." 23:59:59");

        $page = 'dashboard';
        $stats = $this->dashboardStats($kitchen_id,$start,$end);
        if($kitchen_id=="all")
        return view('admin.custom_dashboard', compact('page', 'stats'));
        else
        return view('admin.kitchen_dashboard',compact('page','stats'));

    }



    //Kitchens Stats
    public function dashboardStats($kitchen_id,$start,$end)
    {

      $minutes = 120;
      if($kitchen_id=="all")
      {
        $days_diff = $end->diff($start)->days;
        $stats['end'] = $end->format('m/d/Y');
        $stats['start'] = $start->format('m/d/Y');
        $stats['selected'] = 'Sales between ' . $start->format('d,M Y') . ' - ' . $end->format('d,M Y');
        $stats['total_sales'] = $this->orderRepo->getTotalSalesByDates($start, $end);
        $stats['total_users'] = $this->userRepo->getAllUsersByDates($start, $end)->count();
        $stats['total_orders'] = $this->orderRepo->getAllOrdersByDates($start, $end)->count();
        $stats['last_custom_days_sales'] = $this->orderRepo->getLastDaysSalesByDates($start, $end,$kitchen_id);
        $stats['total_sales_7'] = Cache::remember('total_sales_7', $minutes, function() {return $this->orderRepo->getLastDaysOrder('7');});
        $stats['total_sales_month_0'] = Cache::remember('total_sales_month_0', $minutes, function() {return $this->orderRepo->getLastDaysOrder('30');});
        $stats['total_sales_1'] = $this->orderRepo->getTotalSalesByDays('1');
        $stats['total_cities'] = $this->locationRepo->getAllCities()->count();
        $stats['kitchens'] = $this->kitchenRepo->getAllKitchens();
        $stats['total_orders_7'] = $this->orderRepo->getAllOrdersByDays('7')->count();
        $stats['total_orders_1'] = $this->orderRepo->getAllOrdersByDays('0')->count();
        $stats['total_orders_month_0'] = $this->orderRepo->getAllOrdersByMonths('0')->count();
        $stats['total_users_month_0'] = $this->userRepo->getAllUsersByMonths('0')->count();
        $stats['last_30_days_order'] = $this->orderRepo->getLastDaysOrder('30');
        $stats['days'] = $days_diff;
      }
      else
      {
        $days_diff = $end->diff($start)->days;
        $kitchen = $this->kitchenRepo->getKitchenById($kitchen_id);
        $orders = $this->orderRepo->getAllOrdersByDates($start,$end,$kitchen_id);
        $stats['source_count'] = $orders->groupBy('source_id')->toArray();
        $stats['end'] = $end->format('m/d/Y');
        $stats['start'] = $start->format('m/d/Y');
        $stats['kitchen_name'] = $kitchen->name;
        $stats['kitchen_id'] = $kitchen->id;
        $stats['total_areas'] = $kitchen->areas->count();
        $stats['selected'] = 'Sales between '. $start->format('d,M Y'). ' - ' . $end->format('d,M Y');
        $stats['total_sales'] = $this->orderRepo->getTotalSalesByDates($start,$end,$kitchen_id);
        $stats['total_orders'] = $this->orderRepo->getAllOrdersByDates($start,$end)->where('kitchen_id',$kitchen->id)->count();
        $stats['last_custom_days_sales'] = $this->orderRepo->getLastDaysSalesByDates($start,$end,$kitchen_id);
        $stats['total_sales_7'] = $this->orderRepo->getTotalSalesByDays('7');
        $stats['total_sales_month_0'] = $this->orderRepo->getTotalSales($kitchen_id);
        $stats['total_sales_1'] = $this->orderRepo->getTotalSalesByDays('1',$kitchen_id);
        $stats['kitchens'] = $this->kitchenRepo->getAllKitchens();
        $stats['total_orders_7'] = $this->orderRepo->getAllOrdersByDays('6')->where('kitchen_id',$kitchen->id)->count();
        $stats['total_orders_1'] = $this->orderRepo->getAllOrdersByDays('1')->where('kitchen_id',$kitchen->id)->count();
        $stats['total_orders_month_0'] = $this->orderRepo->getAllOrdersByMonths('0')->where('kitchen_id',$kitchen->id)->count();
        $stats['last_30_days_order'] = $this->orderRepo->getLastDaysOrder('30',$kitchen_id);
        $stats['days'] = $days_diff;
        $stats['sources'] = $this->orderRepo->getAllOrderSources();
        foreach ($stats['sources'] as $source) {
            if(!isset($stats['source_count'][$source->id]))
            {
                $stats['source_count'][$source->id] = null;
            }
            else
            {
                $stats['source_count'][$source->id] = $stats['source_count'][$source->id];
            }
        }
        
      }
      return $stats;
    }

    //Kitchens Specific Stats

    public function kitchenStatsById($id,$start,$end)
    {
      $days_diff = $end->diff($start)->days;
      $stats['end'] = $end->format('m/d/Y');
      $stats['start'] = $start->format('m/d/Y');
      $stats['kitchen_name'] =$this->kitchenRepo->getKitchenById($kitchen_id)->name;
      $stats['selected'] = 'Sales between '. $start->format('d-m-y'). ' - ' . $end->format('d-m-y');
      $stats['total_sales'] = $this->orderRepo->getTotalSalesByDates($start,$end);
      $stats['total_users'] = $this->userRepo->getAllUsersByDates($start,$end)->count();
      $stats['total_orders'] = $this->orderRepo->getAllOrdersByDates($start,$end)->count();
      $stats['last_custom_days_sales'] = $this->orderRepo->getLastDaysSalesByDates($start,$end);
      $stats['total_sales_7'] = $this->orderRepo->getTotalSalesByDays('7');
      $stats['total_sales_month_0'] = $this->orderRepo->getTotalSales();
      $stats['total_sales_1'] = $this->orderRepo->getTotalSalesByDays('1');
      $stats['total_cities'] = $this->locationRepo->getAllCities()->count();
      $stats['kitchens'] = $this->kitchenRepo->getAllKitchens();
      $stats['total_orders_7'] = $this->orderRepo->getAllOrdersByDays('7')->count();
      $stats['total_orders_1'] = $this->orderRepo->getAllOrdersByDays('0')->count();
      $stats['total_orders_month_0'] = $this->orderRepo->getAllOrdersByMonths('0')->count();
      $stats['total_users_month_0'] = $this->userRepo->getAllUsersByMonths('0')->count();
      $stats['last_30_days_order'] = $this->orderRepo->getLastDaysOrder('30');
      $stats['days'] = $days_diff;

      return $stats;

    }

    /**
    *
    *
    */

    public function reviews()
    {
        $page = 'datatables';
        return view('admin.reviews',compact('page'));
    }

}

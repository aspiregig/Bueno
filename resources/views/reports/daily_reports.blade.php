
@extends('admin.master')

@section('title')Bueno Kitchen @endsection

@section('header')


    <!-- stylesheets -->
    @include('admin.partials.css')

    <!-- javascript -->
    @include('admin.partials.js')

    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

@endsection

@section('content')
    <div id="content">
        <div class="menubar">
            <div class="sidebar-toggler visible-xs">
                <i class="ion-navicon"></i>
            </div>
            <div class="page-title">
                Order Report
            </div>

            <div class="period-select hidden-xs">
                <form  method="post" role="form">
                    {{ csrf_field() }}
                    <div class="input-daterange">
                        <div class="input-group input-group-sm">
                <span class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </span>
                            <input name="start" type="text" class="form-control start-date" @if(old('start')) value="{{old('start')}}" @else value="{{$start->format('m/d/Y H:i:s')}}" @endif  required=""/>
                        </div>


                        <p class="pull-left">to</p>

                        <div class="input-group input-group-sm">
                <span class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </span>
                            <input name="end" type="text" class="form-control end-date" @if(old('end')) value="{{old('end')}}" @else value="{{$end->format('m/d/Y H:i:s')}}" @endif required=""/>
                        </div>
                        <div class="input-group input-group-sm " style="margin-left: 10px;">
                <span class="input-group-addon">
                  <i class="fa fa-coffee"></i>
                </span>
                <select class="form-control" data-smart-select name="kitchen_id">
                  <option value="-1" @if($stats['kitchen_id']== -1) selected="" @endif >All Kitchens</option>
                 @foreach($stats['kitchens'] as $kitchen)
                  <option value="{{$kitchen->id}}" @if($stats['kitchen_id']== $kitchen->id) selected="" @endif>{{$kitchen->name}}</option>
                  @endforeach
                </select>
            </div>
                        <div class="input-group input-group-sm" style="margin-left: 10px;padding: 5px;">
                            Download <input name="download" type="checkbox" value="1"/>
                        </div>
                        <button type="submit" style="margin-left:10px;"class="btn btn-primary"> Export</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="content-wrapper">
         @include('admin.partials.flash')
        <div class="metrics clearfix">
            <div class="metric">
                <strong><span class="field">From - To</span></strong>
                <span class="data"><small>{{$start->format('d-m-y H:i') }}</small> to <small>{{$end->format('d-m-y H:i') }}</small></span>
            </div>
            <div class="metric">
                <strong><span class="field">Total Orders</span></strong>
                <span class="data">{{$stats['all_orders']}}</span>
            </div>
            <div class="metric">
                <strong><span class="field">Settled Orders</span></strong>
                <span class="data">{{$stats['settled_orders']}}</span>
            </div>
            <div class="metric">
                <strong><span class="field">Cancelled Orders</span></strong>
                <span class="data">{{$stats['cancelled_orders']}}</span>
            </div>
            <div class="metric">
                <strong><span class="field">Dispatched Orders</span></strong>
                <span class="data">{{$stats['dispatched_orders']}}</span>
            </div>
            <div class="metric">
                <strong><span class="field">Initiated Orders</span></strong>
                <span class="data">{{$stats['initiated_orders']}}</span>
            </div>
            <div class="metric">
                <strong><span class="field">In Kitchen Orders</span></strong>
                <span class="data">{{$stats['kitchen_orders']}}</span>
            </div>
            <div class="metric">
                <strong><span class="field">Unsettled Orders</span></strong>
                <span class="data">{{$stats['unsettled_orders']}}</span>
            </div>
            <div class="metric">
                <strong><span class="field">Pending Orders</span></strong>
                <span class="data">{{$stats['pending_orders']}}</span>
            </div>
            <div class="metric">
                <strong><span class="field">Packed Orders</span></strong>
                <span class="data">{{$stats['packed_orders']}}</span>
            </div>
        </div>

        </div>
        <h1>Overall Stats</h1>
        <div class="metrics clearfix">
            <div class="metric">
                <strong><span class="field">Total Orders</span></strong>
                <span class="data"><small>{{$stats['total_orders']}}</small></span>
            </div>
            <div class="metric">
                <strong><span class="field">Delayed Orders</span></strong>
                <span class="data"><small>{{$stats['delayed_orders']}}</small></span>
            </div>
            <div class="metric">
                <strong><span class="field">Total Sales(pre tax)</span></strong>
                <span class="data"><small>{{$stats['total_revenue']}}</small></span>
            </div>
            <div class="metric">
                <strong><span class="field">Total Sales(post tax)</span></strong>
                <span class="data"><small>{{$stats['total_sales'] }}</small></span>
            </div>
            <div class="metric">
                <strong><span class="field">Total Discount</span></strong>
                <span class="data"><small>{{round($stats['total_discount'],2)}}</small></span>
            </div>
            <div class="metric">
                <strong><span class="field">Total Tax Collected</span></strong>
                <span class="data"><small>{{ $stats['total_tax'] }}</small></span>
            </div>
            <div class="metric">
                <strong><span class="field">Avg. Order Value (Pre Tax)</span></strong>
                <span class="data"><small>{{$stats['total_aov']}}</small></span>
            </div>
            <div class="metric">
                <strong><span class="field">Total Credits Used</span></strong>
                <span class="data"><small>{{round($stats['total_credits_used'],2)}}</small></span>
            </div>
            <div class="metric">
                <strong><span class="field">Total Credits Given</span></strong>
                <span class="data"><small>{{round($stats['total_credits_awarded'],2)}}</small></span>
            </div>
            <div class="metric">
                <strong><span class="field">Total Items sold</span></strong>
                <span class="data"><small>{{$stats['total_items']}}</small></span>
            </div>
            <div class="metric">
                <strong><span class="field">Avg Items/Order</span></strong>
                <span class="data"><small>{{$stats['total_orders']==0 ? 0 : round(($stats['total_items']/$stats['total_orders']),2)}}</small></span>
            </div>
            <div class="metric">
                <strong><span class="field">Total Food Costing</span></strong>
                <span class="data"><small>{{$stats['total_food_cost']}}</small></span>
            </div>
            <div class="metric">
                <strong><span class="field">Total costing(with pkg)</span></strong>
                <span class="data"><small>{{$stats['total_food_and_packaging_cost']}}</small></span>
            </div>
            <div class="metric">
                <strong><span class="field">Avg Completion Time</span></strong>
                <span class="data"><small>{{round($stats['avg_order_time'],2)}} Mins</small></span>
            </div>
            <div class="metric">
                <strong><span class="field">Avg Kitchen Time</span></strong>
                <span class="data"><small>{{round($stats['avg_kitchen_time'],2)}} Mins</small></span>
            </div>
            <div class="metric">
                <strong><span class="field">Avg Delivery Time</span></strong>
                <span class="data"><small>{{round($stats['avg_rider_time'],2)}} Mins</small></span>
            </div>
            <!--<div class="metric">
                <strong><span class="field">Avg Customer distance</span></strong>
                <span class="data"><small>{{round($stats['avg_customer_distance'],2)}} Kms</small></span>
            </div>-->
            <div class="metric">
                <strong><span class="field">Feedback</span></strong>
                <span class="data"><small>Happy: {{$stats['feedback']['happy']}}, Sad: {{$stats['feedback']['sad']}}</small></span>
            </div>
        </div>
        <!-- Summary -->
        <h1>Users Wise</h1>
        <table>
            <thead>
            <tr>
                <td>Users</td>
                <td>COD</td>
                <td>Online</td>
                <td>Orders</td>
                <td>Amount(Pre tax)</td>
                <td>Avg AOV (Pre tax)</td>
                <td>% contribution</td>
            </tr>
            </thead>
            <tbody>
               <tr>
                   <td>New</td>
                   <td>{{$stats['new_users']['cod']}}</td>
                   <td>{{$stats['new_users']['online']}}</td>
                   <td>{{$stats['new_users']['count']}}</td>
                   <td>{{$stats['new_users']['cost']}}</td>
                   <td>{{$stats['new_users']['aov']}}</td>
                   <td>{{$stats['new_users']['percent']}}%</td>
               </tr>
               <tr>
                   <td>Old</td>
                   <td>{{$stats['old_users']['cod']}}</td>
                   <td>{{$stats['old_users']['online']}}</td>
                   <td>{{$stats['old_users']['count']}}</td>
                   <td>{{$stats['old_users']['cost']}}</td>
                   <td>{{$stats['old_users']['aov']}}</td>
                   <td>{{$stats['old_users']['percent']}}%</td>
               </tr>
           </tbody>
           <tfoot>
            <tr>
                <th>Total</th>
                <th>{{$stats['cod_count']}}</th>
                <th>{{$stats['online_count']}}</th>
                <th>{{ $stats['total_orders'] }}</th>
                <th>{{ $stats['total_revenue'] }}</th>
                <th>{{ $stats['total_aov'] }}</th>
                <th>100%</th>
            </tr>
            </tfoot>
            </table>
        <br/>

        @if (isset($stats['coupon']))
        <h1>Coupon wise Sales (Pre tax)</h1>
        <table class="reports-datatable">
            <thead>
            <tr>
                <td>Coupon Name</td>
                <td>Orders</td>
                <td>Sales</td>
                <td>Discount</td>
                <td>Credits Rewarded</td>
                <td>Avg AOV (Pre tax)</td>
                <td>% contribution</td>
            </tr>
            </thead>
            <tbody>
            @foreach ($stats['coupon'] as $coupon_code => $coupon_values)
                <tr>
                    <td>{{ $coupon_code }}</td>
                    <td>{{ $stats['coupon'][$coupon_code]['count']}}</td>
                    <td>{{ $stats['coupon'][$coupon_code]['cost']}}</td>
                    <td>{{ $stats['coupon'][$coupon_code]['discount']}}</td>
                    <td>{{ $stats['coupon'][$coupon_code]['credits_awarded']}}</td>
                    <td>{{ $stats['coupon'][$coupon_code]['count']==0 ? 0 : round(($stats['coupon'][$coupon_code]['cost']/$stats['coupon'][$coupon_code]['count']), 2)}}</td>
                    <td>{{ $stats['total_revenue']==0 ? 0 : round((($stats['coupon'][$coupon_code]['cost']*100)/$stats['total_revenue']), 2)}}%</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <br/>
        @endif

        <h1>Outlet Wise Sales (Pre tax)</h1>
        <table>
            <thead>
            <tr>
                <td>Outlet Wise</td>
                <td>COD Orders</td>
                <td>COD Sales</td>
                <td>COD Sales(Post Tax)</td>
                <td>Online Orders</td>
                <td>Online Sales</td>
                <td>Total Orders</td>
                <td>Amount</td>
                <td>Amount(Post tax)</td>
                <td>Cancelled Orders</td>
                <td>Avg AOV (Pre tax)</td>
                <td>% contribution</td>
            </tr>
            </thead>
            <tbody>
           @foreach($kitchens as $kitchen)
               <tr>
                   <td>{{ $kitchen->name }}</td>
                   <td>{{ $stats['kitchen'][$kitchen->id]['cod']}}</td>
                   <td>{{ $stats['kitchen'][$kitchen->id]['cod_amount']}}</td>
                   <td>{{ $stats['kitchen'][$kitchen->id]['cod_amount_post_tax']}}</td>
                   <td>{{ $stats['kitchen'][$kitchen->id]['online']}}</td>
                   <td>{{ $stats['kitchen'][$kitchen->id]['online_amount']}}</td>
                   <td>{{ $stats['kitchen'][$kitchen->id]['count']}}</td>
                   <td>{{ $stats['kitchen'][$kitchen->id]['cost'] }}</td>
                   <td>{{ $stats['kitchen'][$kitchen->id]['cost_post'] }}</td>
                   <td>{{ $stats['kitchen'][$kitchen->id]['cancelled_orders'] }}</td>
                   <td>{{ $stats['kitchen'][$kitchen->id]['count']==0 ? 0 : round(($stats['kitchen'][$kitchen->id]['cost']/$stats['kitchen'][$kitchen->id]['count']), 2)}}</td>
                   <td>{{ $stats['total_revenue']==0 ? 0 : round((($stats['kitchen'][$kitchen->id]['cost']*100)/$stats['total_revenue']), 2)}}%</td>
               </tr>
           @endforeach
           </tbody>
           <tfoot>
            <tr>
                <th>Total</th>
                <th>{{$stats['cod_count']}}</th>
                <th>{{$stats['cod_sales']}}</th>
                <th>{{$stats['cod_sales_with_tax']}}</th>
                <th>{{$stats['online_count']}}</th>
                <th>{{$stats['online_sales']}}</th>
                <th>{{ $stats['total_orders'] }}</th>
                <th>{{ $stats['total_revenue'] }}</th>
                <th>{{$stats['total_sales']}}</th>
                <th>{{ $stats['cancelled_orders'] }}</th>
                <th>{{ $stats['total_aov'] }}</th>
                <th>100%</th>
            </tr>
            </tfoot>
            </table>
        <br/>

        @if (isset($stats['paymodes']))
        <h1>Payment mode wise Sales (Post tax)</h1>
        <table class="reports-datatable">
            <thead>
            <tr>
                <td>Payment Mode</td>
                <td>Orders</td>
                <td>Sales</td>
                <td>Avg AOV (Pre tax)</td>
                <td>% contribution</td>
            </tr>
            </thead>
            <tbody>
            @foreach ($stats['paymodes'] as $paymode => $paymode_values)
                <tr>
                    <td>{{ $paymode }}</td>
                    <td>{{ $stats['paymodes'][$paymode]['count']}}</td>
                    <td>{{ $stats['paymodes'][$paymode]['cost']}}</td>
                    <td>{{ $stats['paymodes'][$paymode]['count']==0 ? 0 : round(($stats['paymodes'][$paymode]['cost']/$stats['paymodes'][$paymode]['count']), 2)}}</td>
                    <td>{{ $stats['total_revenue']==0 ? 0 : round((($stats['paymodes'][$paymode]['cost']*100)/$stats['total_revenue']), 2)}}%</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <br/>
        @endif

        @if (isset($stats['riders']))
        <h1>Rider's performance</h1>
        <table class="reports-datatable">
                <thead>
                <tr>
                    <td>Rider Name</td>
                    <td>Orders</td>
                    <td>Delayed Orders</td>
                    <td>Avg. delivery time</td>
                    <td>COD Cash</td>
                </tr>
                </thead>
                <tbody>
                @foreach ($stats['riders'] as $rider => $rider_values)
                    <tr>
                        <td>{{ $rider }}</td>
                        <td>{{ $stats['riders'][$rider]['count']}}</td>
                        <td>{{ $stats['riders'][$rider]['delayed_count']}}</td>
                        <td>{{ $stats['riders'][$rider]['count'] ? round($stats['riders'][$rider]['total_time']/$stats['riders'][$rider]['count'],2) : 0}}</td>
                        <td>{{ $stats['riders'][$rider]['total_cod']}}</td>
                    </tr>
                @endforeach
                </tbody>
        </table>
        <br/>
        @endif

        <h1>Time Slot Wise</h1>
        <table>
            <thead>
            <tr>
                <th>Timeslot Wise</th>
                <th>No. of Orders</th>
                <th>Amount(Pre tax)</th>
                <td>Avg AOV (Pre tax)</td>
                <td>% contribution</td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Midnight (00:00-05:59)</td>
                <td>{{ $stats['time']['midnight_count'] }}</td>
                <td>{{ $stats['time']['midnight_cost'] }}</td>
                <td>{{ $stats['time']['midnight_count']==0 ? 0 : round(($stats['time']['midnight_cost']/$stats['time']['midnight_count']), 2)}}</td>
                <td>{{ $stats['total_revenue']==0 ? 0 : round((($stats['time']['midnight_cost']*100)/$stats['total_revenue']), 2)}}%</td>
            </tr>
            <tr>
                <td>Breakfast (06:00-11:59)</td>
                <td>{{ $stats['time']['breakfast_count'] }}</td>
                <td>{{ $stats['time']['breakfast_cost'] }}</td>
                <td>{{ $stats['time']['breakfast_count']==0 ? 0 : round(($stats['time']['breakfast_cost']/$stats['time']['breakfast_count']), 2)}}</td>
                <td>{{ $stats['total_revenue']==0 ? 0 : round((($stats['time']['breakfast_cost']*100)/$stats['total_revenue']), 2)}}%</td>
            </tr>
            <tr>
                <td>Lunch (12:00-15:59)</td>
                <td>{{ $stats['time']['lunch_count'] }}</td>
                <td>{{ $stats['time']['lunch_cost'] }}</td>
                <td>{{ $stats['time']['lunch_count']==0 ? 0 : round(($stats['time']['lunch_cost']/$stats['time']['lunch_count']), 2)}}</td>
                <td>{{ $stats['total_revenue']==0 ? 0 : round((($stats['time']['lunch_cost']*100)/$stats['total_revenue']), 2)}}%</td>
            </tr>
            <tr>
                <td>Snacks (16:00-19:59)</td>
                <td>{{ $stats['time']['snacks_count'] }}</td>
                <td>{{ $stats['time']['snacks_cost'] }}</td>
                <td>{{ $stats['time']['snacks_count']==0 ? 0 : round(($stats['time']['snacks_cost']/$stats['time']['snacks_count']), 2)}}</td>
                <td>{{ $stats['total_revenue']==0 ? 0 : round((($stats['time']['snacks_cost']*100)/$stats['total_revenue']), 2)}}%</td>
            </tr>
            <tr>
                <td>Dinner (20:00-23:59)</td>
                <td>{{ $stats['time']['dinner_count'] }}</td>
                <td>{{ $stats['time']['dinner_cost'] }}</td>
                <td>{{ $stats['time']['dinner_count']==0 ? 0 : round(($stats['time']['dinner_cost']/$stats['time']['dinner_count']), 2)}}</td>
                <td>{{ $stats['total_revenue']==0 ? 0 : round((($stats['time']['dinner_cost']*100)/$stats['total_revenue']), 2)}}%</td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <th>Total</th>
                <th>{{ $stats['total_orders'] }}</th>
                <th>{{ $stats['total_revenue'] }}</th>
                <th>{{ $stats['total_aov'] }}</th>
                <th>100%</th>
            </tr>
            </tfoot>
        </table>
        <br/>

        <h1>Source Wise</h1>
        <table class="reports-datatable">
            <thead>
            <tr>
                <th>Source Wise</th>
                <th>No. of Orders</th>
                <th>Amount(Pre tax)</th>
                <td>Avg AOV (Pre tax)</td>
                <td>% contribution</td>
            </tr>
            </thead>
            <tbody>
            @foreach($sources as $source)
                <tr>
                    <td>{{ $source->name }}</td>
                    <td>{{ $stats['source'][$source->id]['count'] }}</td>
                    <td>{{ $stats['source'][$source->id]['cost'] }}</td>
                    <td>{{ $stats['source'][$source->id]['count']==0 ? 0 : round(($stats['source'][$source->id]['cost']/$stats['source'][$source->id]['count']), 2)}}</td>
                    <td>{{ $stats['total_revenue']==0 ? 0 : round((($stats['source'][$source->id]['cost']*100)/$stats['total_revenue']), 2)}}%</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th>Total</th>
                <th>{{ $stats['total_orders'] }}</th>
                <th>{{ $stats['total_revenue'] }}</th>
                <th>{{ $stats['total_aov'] }}</th>
                <th>100%</th>
            </tr>
            </tfoot>
            </table>
        <br/>

        <h1>Location Wise</h1>
        <table class="reports-datatable">
    <thead>
    <tr>
        <th>Location Wise</th>
        <th>No. of Orders</th>
        <th>Amount(Pre tax)</th>
        <th>Avg AOV (Pre tax)</th>
        <th>% contribution</th>
    </tr>
    </thead>
    <tbody>
    @foreach($areas as $area)
        @if(isset($stats['area'][$area->id]))
        <tr>
            <td>{{ $area->name }}</td>
            <td>{{ $stats['area'][$area->id]['count'] }}</td>
            <td>{{ $stats['area'][$area->id]['cost'] }}</td>
            <td>{{ $stats['area'][$area->id]['count']==0 ? 0 : round(($stats['area'][$area->id]['cost']/$stats['area'][$area->id]['count']), 2)}}</td>
            <td>{{ $stats['total_revenue']==0 ? 0 : round((($stats['area'][$area->id]['cost']*100)/$stats['total_revenue']), 2)}}%</td>
        </tr>
        @endif
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>Total</th>
        <th>{{ $stats['total_orders'] }}</th>
        <th>{{ $stats['total_revenue'] }}</th>
        <th>{{ $stats['total_aov'] }}</th>
        <th>100%</th>
    </tr>
    </tfoot>
    </table>
        <br/>

        <h1>Item Wise</h1>
        <table class="reports-datatable">
    <thead>
        <th>Item Wise</th>
        <th>Qty. Sold(Total)</th>
        <th>Sale(Total)</th>
        <th>Food Costing</th>
        <th>Total Costing(w/pkg)</th>
        <th>% contribution</th>
    </thead>
    @foreach($items as $item)
        @if(isset($stats['item'][$item->id]))
        <tr>
            <td>{{$item->itemable->name}}</td>
            <td>{{ $stats['item'][$item->id]['count'] }}</td>
            <td>{{ $stats['item'][$item->id]['cost'] }}</td>
            <td>{{ $stats['item'][$item->id]['food_cost'] }}</td>
            <td>{{ $stats['item'][$item->id]['total_cost'] }}</td>
            <td>{{ $stats['total_revenue']==0 ? 0 : round((($stats['item'][$item->id]['cost']*100)/$stats['total_revenue']), 2)}}%</td>
        </tr>
        @endif
    @endforeach
    </table>
        <br/>

        <h1>MultiCuisine Report</h1>
        <table>
        <thead>
        </thead>
            <tr>
                <td>1 Dish      : {{$stats['one_item']['count']}}</td>
                <td>Total Value : {{$stats['one_item']['cost']}}</td>
            </tr>
            <tr>
                <td>1+ Dish     : {{$stats['more_item']['count']}}</td>
                <td>Total Value : {{$stats['more_item']['cost']}}</td>
            </tr>
        </tbody>
        <tfoot>
            <th>Total Orders     :{{ $stats['total_orders'] }}</th>
            <th>Total Value      :{{ $stats['total_revenue'] }}</th>
        </tfoot>
        </table>
</div>


@endsection

@section('script')

    <script type="text/javascript">

        $('.reports-datatable').dataTable({
            "sPaginationType": "full_numbers",
            "iDisplayLength": 10,
            "aLengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]]
        });

    </script>

@endsection
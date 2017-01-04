
            <table>
            <thead>
            <tr>
               <th> Report</th>
            </tr>
            </thead>
            <tr>
            <td>From</td>
            <td>{{$start->format('l ,d M Y H:i:s') }}</td>
            </tr>
            <tr>
            <td>To</td>
            <td>{{$end->format('l ,d M Y H:i:s') }}</td>
            </tr>
                <tr>
                    <td>Total Orders</td>
                    <td>{{$stats['all_orders']}}</td>
                </tr>
                <tr>
                    <td>Settled Orders</td>
                    <td>{{$stats['settled_orders']}}</td>
                </tr>
                <tr>
                    <td>Cancelled Orders</td>
                    <td>{{$stats['cancelled_orders']}}</td>
                </tr>
                <tr>
                    <td>Dispatched Orders</td>
                    <td>{{$stats['dispatched_orders']}}</td>
                </tr>
                <tr>
                    <td>Initiated Orders</td>
                    <td>{{$stats['initiated_orders']}}</td>
                </tr>
                <tr>
                    <td>Kitchen Orders</td>
                    <td>{{$stats['kitchen_orders']}}</td>
                </tr>
                <tr>
                    <td>Unsettled Orders</td>
                    <td>{{$stats['unsettled_orders']}}</td>
                </tr>
                <tr>
                    <td>Pending Orders</td>
                    <td>{{$stats['pending_orders']}}</td>
                </tr>
                <tr>
                    <td>Packed Orders</td>
                    <td>{{$stats['packed_orders']}}</td>
                </tr>
        </table>
        <table>
        <table>
            <thead>
            <tr>
               <th> Overall Stats</th>
            </tr>
            </thead>
            <tr>
                <td>Total Orders</td>
                <td>{{$stats['total_orders']}}</td>
            </tr>
            <tr>
                <td>Total Sales(pre tax)</td>
                <td>{{round($stats['total_revenue'],2)}}</td>
            </tr>
            <tr>
                <td>Total Sales(post tax)</td>
                <td>{{round($stats['total_sales'],2)}}</td>
            </tr>
            <tr>
                <td>Total Discount</td>
                <td>{{round($stats['total_discount'],2)}}</td>
            </tr>
            <tr>
                <td>Total Tax Collected</td>
                <td>{{round($stats['total_tax'],2)}}</td>
            </tr>
            <tr>
                <td>Avg. Order Value (Pre Tax)</td>
                <td>{{round($stats['total_aov'],2)}}</td>
            </tr>
            <tr>
                <td>Total Credits Used</td>
                <td>{{round($stats['total_credits_used'],2)}}</td>
            </tr>
            <tr>
                <td>Avg. Completion Time</td>
                <td>{{round($stats['avg_order_time'],2)}}</td>
            </tr>
            <tr>
                <td>Avg Delivery Time</td>
                <td>{{round($stats['avg_rider_time'],2)}}</td>
            </tr>
            <tr>
                <td>Feedback</td>
                <td>Happy: {{$stats['feedback']['happy']}}, Sad: {{$stats['feedback']['sad']}}</td>
            </tr>
        </table>
<!-- Summary -->

<table>
    <thead>
    <tr>
        <th>Users Wise</th>
    </tr>
    </thead>
    <tr>
        <td>Users</td>
        <td>COD</td>
        <td>Online</td>
        <td>Orders</td>
        <td>Amount(Pre tax)</td>
    </tr>
    <tbody>
       <tr>
           <td>New Users</td>
           <td>{{$stats['new_users']['cod']}}</td>
           <td>{{$stats['new_users']['online']}}</td>
           <td>{{$stats['new_users']['count']}}</td>
           <td>{{$stats['new_users']['cost']}}</td>
       </tr>
       <tr>
           <td>Old Users</td>
           <td>{{$stats['old_users']['cod']}}</td>
           <td>{{$stats['old_users']['online']}}</td>
           <td>{{$stats['old_users']['count']}}</td>
           <td>{{$stats['old_users']['cost']}}</td>
       </tr>
   </tbody>
   <tfoot>
    <tr>
        <th>Total</th>
        <th>{{$stats['cod_count']}}</th>
        <th>{{$stats['online_count']}}</th>
        <th>{{ $stats['total_orders'] }}</th>
        <th>{{ $stats['total_revenue'] }}</th>
    </tr>
    </tfoot>
    </table>
<br/>

<table>
    <thead>
    <tr>
        <td>Outlet Wise</td>
        <td>COD Orders</td>
        <td>COD Sales</td>
        <td>Online Orders</td>
        <td>Online Sales</td>
        <td>Total Orders</td>
        <td>Amount</td>
        <td>Amount(Post tax)</td>
        <td>Cancelled Orders</td>
    </tr>
    </thead>
    <tbody>
   @foreach($kitchens as $kitchen)
       <tr>
           <td>{{ $kitchen->name }}</td>
           <td>{{ $stats['kitchen'][$kitchen->id]['cod']}}</td>
           <td>{{ $stats['kitchen'][$kitchen->id]['cod_amount']}}</td>
           <td>{{ $stats['kitchen'][$kitchen->id]['online']}}</td>
           <td>{{ $stats['kitchen'][$kitchen->id]['online_amount']}}</td>
           <td>{{ $stats['kitchen'][$kitchen->id]['count']}}</td>
           <td>{{ $stats['kitchen'][$kitchen->id]['cost'] }}</td>
           <td>{{ $stats['kitchen'][$kitchen->id]['cost_post'] }}</td>
           <td>{{ $stats['kitchen'][$kitchen->id]['cancelled_orders'] }}</td>
       </tr>
   @endforeach
   </tbody>
   <tfoot>
    <tr>
        <th>Total</th>
        <th>{{$stats['cod_count']}}</th>
        <th>{{$stats['cod_sales']}}</th>
        <th>{{$stats['online_count']}}</th>
        <th>{{$stats['online_sales']}}</th>
        <th>{{ $stats['total_orders'] }}</th>
        <th>{{ $stats['total_revenue'] }}</th>
        <th>{{$stats['total_sales']}}</th>
        <th>{{ $stats['cancelled_orders'] }}</th>
    </tr>
    </tfoot>
    </table>
<br/>
   
        
    <table>
    <thead>
    <tr><th>Time Slot Wise</th></tr>
    </thead>
    <tr>
        <th>Timeslot Wise</th>
        <th>No. of Orders</th>
        <th>Amount</th>
    </tr>
    <tbody>
    <tr>
        <td>Midnight (00:00-05:59)</td>
        <td>{{ $stats['time']['midnight_count'] }}</td>
        <td>{{ $stats['time']['midnight_cost'] }}</td>
    </tr>
    <tr>
        <td>Breakfast (06:00-11:59)</td>
        <td>{{ $stats['time']['breakfast_count'] }}</td>
        <td>{{ $stats['time']['breakfast_cost'] }}</td>
    </tr>
    <tr>
        <td>Lunch (12:00-15:59)</td>
        <td>{{ $stats['time']['lunch_count'] }}</td>
        <td>{{ $stats['time']['lunch_cost'] }}</td>
    </tr>
    <tr>
        <td>Snacks (16:00-19:59)</td>
        <td>{{ $stats['time']['snacks_count'] }}</td>
        <td>{{ $stats['time']['snacks_cost'] }}</td>
    </tr>
    <tr>
        <td>Dinner (20:00-23:59)</td>
        <td>{{ $stats['time']['dinner_count'] }}</td>
        <td>{{ $stats['time']['dinner_cost'] }}</td>
    </tr>
    </tbody>
    <tfoot>
    <tr>
        <th>Total</th>
        <th>{{ $stats['total_orders'] }}</th>
        <th>{{ $stats['total_revenue'] }}</th>
    </tr>
    </tfoot>
    </table>
    
    <table>
    <thead>
    <tr><th>Source Wise</th></tr>
    </thead>
    <tr>
        <th>Source Wise</th>
        <th>No. of Orders</th>
        <th>Amount</th>
    </tr>
    <tbody>
    @foreach($sources as $source)
        <tr>
            <td>{{ $source->name }}</td>
            <td>{{ $stats['source'][$source->id]['count'] }}</td>
            <td>{{ $stats['source'][$source->id]['cost'] }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>Total</th>
        <th>{{ $stats['total_orders'] }}</th>
        <th>{{ $stats['total_revenue'] }}</th>
    </tr>
    </tfoot>
    </table>

    <tr></tr>
    <tr><td><h1>Location Wise</h1></td></tr>
    <tr></tr>
    </table>
    <table class="reports-datatable">
    <thead>
    <tr>
        <th>Location Wise</th>
        <th>No. of Orders</th>
        <th>Amount</th>
    </tr>
    </thead>
    <tbody>
    @foreach($areas as $area)
        @if(isset($stats['area'][$area->id]))
        <tr>
            <td>{{ $area->name }}</td>
            <td>{{ $stats['area'][$area->id]['count'] }}</td>
            <td>{{ $stats['area'][$area->id]['cost'] }}</td>
        </tr>
        @endif
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>Total</th>
        <th>{{ $stats['total_orders'] }}</th>
        <th>{{ $stats['total_revenue'] }}</th>
    </tr>
    </tfoot>
    </table>
    
    <br/>
    <h1>Item Wise</h1>
    <table class="reports-datatable">
    <thead>
        <th>Item Wise</th>
        <th>Qty. Sold</th>
        <th>Sale Cost</th>
    </thead>
    @foreach($items as $item)
        @if(isset($stats['item'][$item->id]))
        <tr>
            <td>{{$item->itemable->name}}</td>
            <td>{{ $stats['item'][$item->id]['count'] }}</td>
            <td>{{ $stats['item'][$item->id]['cost'] }}</td>
        </tr>
        @endif
    @endforeach
    </table>
    <tr>

    <br/>

    <h1>MultiCuisine Report</h1>
    <table>
    <thead>
        <th>Multi Cuisine</th>
        <th>Total Orders</th>
        <th>Total Value</th>
        <th>1 Dish</th>
        <th>Total Value</th>
        <th>1+ Dish</th>
        <th>Total Value</th>
    </thead>
        <tr>
            <td></td>
            <td>{{ $stats['total_orders'] }}</td>
            <td>{{ $stats['total_revenue'] }}</td>
            <td>{{$stats['one_item']['count']}}</td>
            <td>{{$stats['one_item']['cost']}}</td>
            <td>{{$stats['more_item']['count']}}</td>
            <td>{{$stats['more_item']['cost']}}</td>
        </tr>
        
    </tbody>
    </table>
</div>
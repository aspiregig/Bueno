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
          Dashboard
        </div>
        <div class="period-select hidden-xs">
          
        </div>
      </div>

      <div class="content-wrapper">
        <div class="metrics clearfix">
          
          
          <div class="metric">
            <span class="field">Kitchen's Name</span>
            <span class="data">{{$kitchen->name}}</span>
          </div>
          <div class="metric">
            <span class="field">Areas Covered</span>
            <span class="data">{{$kitchen->areas->count()}}</span>
         </div>
          <div class="metric">
            <span class="field">Today's Orders</span>
            <span class="data">  {{$stats['total_orders_1']}}   </span>
          </div>
          <div class="metric">
            <span class="field">Orders this week</span>
            <span class="data">  {{$stats['total_orders_7']}}  </span>
          </div>
          <div class="metric">
            <span class="field">Orders this month</span>
            <span class="data"> {{$stats['total_orders_month_0']}}  </span>
          </div>
          <div class="metric">
            <span class="field">Total Orders</span>
            <span class="data">  {{$stats['total_orders']}}  </span>
          </div>
          <div class="metric">
            <span class="field">Today's Sales</span>
            <span class="data"> INR {{$stats['total_sales_1']}} </span>
          </div>
          <div class="metric">
            <span class="field">Sales this week</span>
            <span class="data"> INR {{$stats['total_sales_7']}} </span>
          </div>
          <div class="metric">
            <span class="field">Sales this month</span>
            <span class="data"> INR {{$stats['total_sales_month_0']}}</span>
          </div>
          <div class="metric">
            <span class="field">Total Sales</span>
            <span class="data"> INR {{$stats['total_sales']}}</span>
          </div>
        </div>
        <div class="chart">
          <h3>
          @if(isset($stats['selected']))
          {{$stats['selected']}}
          @else
            Sales last 30 Days
          @endif

            <div class="total pull-right hidden-xs">
            </div>
          </h3>
          <div id="sales-chart" style="height: 230px;position: relative;"></div>
        </div> 
        

          </div>
        <div class="row">
          
          <div class="col-sm-6">
            <div class="barchart">
              <h3>Orders last month</h3>
              <div id="bar-chart"></div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="referrals">
              <h3>Order Referrals</h3>
              @foreach($stats['sources'] as $source)
              <div class="referral">
                <span>
                  {{$source->name}}

                  <div class="pull-right">
                    <span class="data">{{$source->orders->count()}}</span> @if($stats['total_orders']==0 ) 0 @else {{round(($source->orders->count()*100)/$stats['total_orders'],2)}}% @endif
                  </div>
                </span>
                <div class="progress">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: @if($stats['total_orders']==0 ) 0% @else {{($source->orders->count()*100)/$stats['total_orders']}}% @endif">
                    </div>
                </div>
              </div>
            @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @endsection

  @section('script')

     <script type="text/javascript">
    $(function () {
      
          // Range Datepicker
          $('.input-daterange').datepicker({
            autoclose: true,
            orientation: 'right top',
            endDate: new Date()
          });

          // Flot Charts
          var chart_border_color = "#efefef";
      var chart_color = "#b0b3e3";

      

      
      var options = {
        xaxis : {
          mode : "time",
          tickLength : 10
        },
        series : {
          lines : {
            show : true,
            lineWidth : 2,
            fill : true,
            fillColor : {
              colors : [{
                opacity : 0.04
              }, {
                opacity : 0.1
              }]
            }
          },
          shadowSize : 0
        },
        selection : {
          mode : "x"
        },
        grid : {
          hoverable : true,
          clickable : true,
          tickColor : chart_border_color,
          borderWidth : 0,
          borderColor : chart_border_color,
        },
        tooltip : true,
        colors : [chart_color]
      };
    
      
      

     
      
    });

$(window).bind("load", function() {

  // Flot Charts
      var chart_border_color = "#efefef";
      var chart_color = "#b0b3e3";

  var d2 = [[utils.get_timestamp(15), 2950],[utils.get_timestamp(14), 3500], [utils.get_timestamp(13), 2600], [utils.get_timestamp(12), 2630], [utils.get_timestamp(11), 3310], [utils.get_timestamp(10), 2530], [utils.get_timestamp(9), 3050], [utils.get_timestamp(8), 3310], [utils.get_timestamp(7), 2050], [utils.get_timestamp(6), 2125], [utils.get_timestamp(5), 3400], [utils.get_timestamp(4), 3600], [utils.get_timestamp(3), 3930], [utils.get_timestamp(2), 2000], [utils.get_timestamp(1), 2320],];
  

  var options = {
        xaxis : {
          mode : "time",
          tickLength : 10
        },
        series : {
          lines : {
            show : true,
            lineWidth : 2,
            fill : true,
            fillColor : {
              colors : [{
                opacity : 0.04
              }, {
                opacity : 0.1
              }]
            }
          },
          shadowSize : 0
        },
        selection : {
          mode : "x"
        },
        grid : {
          hoverable : true,
          clickable : true,
          tickColor : chart_border_color,
          borderWidth : 0,
          borderColor : chart_border_color,
        },
        tooltip : true,
        colors : [chart_color]
      };

 
// Bar chart (saless)

      var dBar = [
      @foreach($stats['last_30_days_order'] as $key => $value)[utils.get_timestamp({{30-$key}}), {{$value}}], @endforeach
      ];

      var options2 = {
        yaxes: {
              min: 0
          },
        xaxis : {
          mode : "time",
          timeformat: "%a %d"
        },
        series : {
          bars : {
            show : true,
            lineWidth: 0,
            barWidth: 47000000, // for bar charts, this is width in milliseconds (86400000 would be the width of a day)
            align: 'center',
            fillColor : {
              colors : [{ opacity : 0.7 }, { opacity : 0.7 }]
            }
          }
        },
        grid : {
          show: true,
          hoverable : true,
          clickable : true,
          tickColor : chart_border_color,
          borderWidth : 0,
          borderColor : chart_border_color,
        },
        tooltip : true,
        tooltipOpts : {
          content : "Orders on <b>%x</b>: <span class='value'>%y</span>",
          defaultTheme : false,
          shifts: {
            x: -65,
            y: -70
          }
        },
        colors : ["#4fa3d5"]
      };

      var plot4 = $.plot($("#bar-chart"), [dBar], options2);

//Sales Chart 

var d = [
      @foreach($stats['last_16_days_sales'] as $key => $value)[utils.get_timestamp({{16-$key}}), {{$value}}], @endforeach
      ];


      var plot = $.plot($("#sales-chart"), [d], $.extend(options, {
        tooltipOpts : {
          content : "Payments on <b>%x</b>: <span class='value'>INR %y</span>",
          defaultTheme : false,
          shifts: {
            x: -75,
            y: -70
          }
        }
      }));
});
  </script>

  @endsection



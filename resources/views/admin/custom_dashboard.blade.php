@extends('admin.master')

  @section('title')Bueno @endsection

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
          <form  method="post" role="form">
          {{ csrf_field() }}
          <div class="input-daterange">
            <div class="input-group input-group-sm">
                <span class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </span>
                <input name="start" type="text" class="form-control datepicker" value="{{$stats['start']}}" />
            </div>
            
            <p class="pull-left">to</p>

            <div class="input-group input-group-sm">
                <span class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </span>
                <input name="end" type="text" class="form-control datepicker" value="{{$stats['end']}}" />
            </div>
            <p class="pull-left"> - </p>
            <div class="input-group input-group-sm ">
                <span class="input-group-addon">
                  <i class="fa fa-coffee"></i>
                </span>
                
                <select class="form-control" data-smart-select name="kitchen_id">
                  <option value="all" >All Kitchens</option>
                 @foreach($stats['kitchens'] as $kitchen)
                  <option value="{{$kitchen->id}}">{{$kitchen->name}}</option>
                  @endforeach
                </select>
            </div>
            <p class="pull-left"> - </p>
            <button type="submit" class="btn btn-primary"> Apply</button>
            </div>
          </form>
        </div>
      </div>

      <div class="content-wrapper">
        <div class="metrics clearfix">
          
          <div class="metric">
            <span class="field">New users <small>(this month)</small></span>
            <span class="data">{{$stats['total_users_month_0']}}</span>
          </div>
          <div class="metric">
            <span class="field">Kitchens</span>
            <span class="data">{{$stats['kitchens']->count()}}</span>
          </div>
          <div class="metric">
            <span class="field">Cities Covered</span>
            <span class="data">{{$stats['total_cities']}}</span>
         </div>
          <div class="metric">
            <span class="field">Total users</span>
            <span class="data">{{$stats['total_users']}}</span>
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
            Sales last 2 weeks
          @endif

            <div class="total pull-right hidden-xs">
            </div>
          </h3>
          <div id="sales-chart" style="height: 230px;position: relative;"></div>
        </div> 
        

          </div>
        <div class="row">
          
          
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


// Bar chart (sales)

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


//Sales Chart 

var d = [
      @foreach($stats['last_custom_days_sales'] as $key => $value)[utils.get_timestamp({{$stats['days']-$key}}), {{$value}}], @endforeach
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



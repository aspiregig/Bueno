@extends('admin.master')

  @section('title') Bueno Kitchen @endsection

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
          Stock Report
        </div>
        <div class="options pull-right">
          <a href="{{URL::route('admin.stock.export')}}"><i class="fa fa-download"></i> Download</a>
        </div>
      </div>

      <div class="content-wrapper clearfix">

        <div class="results" style="width: 100%;">

          

          
          <table id="datatable-example">
                      <thead>
                          <tr>
                              <th tabindex="0" rowspan="1" colspan="1">Category
                              </th>
                              <th tabindex="0" rowspan="1" colspan="1">Name
                              </th>
                              <th tabindex="0" rowspan="1" colspan="1">Quantity
                              </th>
                              <th tabindex="0" rowspan="1" colspan="1">Measurement Unit
                              </th>
                              <th tabindex="0" rowspan="1" colspan="1">Unit Volume
                              </th>
                              <th tabindex="0" rowspan="1" colspan="1">Rate/Unit
                              </th>
                              <th tabindex="0" rowspan="1" colspan="1">Value
                              </th>
                          </tr>
                      </thead>
                      <tbody>
                      @foreach($meals as $meal)
                          <tr>
                             
                              <td>
                                {{$meal->category->name}}
                              </td>
                              <td><a href="{{URL::route('admin.update_meal',$meal->id)}}">{{$meal->name}}</a></td>
                              <td>{{$meal->stock}}
                              </td>
                              <td class="center">{{$meal->original_price}}</td>
                              <td class="center">{{$meal->discount_price}}</td>
                              <td class="center">{{$meal->original_price}}</td>
                              <td class="center">{{$meal->original_price}}</td>

                          </tr>
                          @endforeach
                      </tbody>
                  </table>
        </div>

      </div>
    </div>

  @endsection

  @section('script')

  <script type="text/javascript">
    $(function () {
      var $filters = $(".filters .filter input:checkbox");
      
      $filters.change(function () {
        var $option = $(this).closest(".filter").find(".filter-option");

        if ($(this).is(":checked")) {
          $option.slideDown(150, function () {
            $option.find("input:text:eq(0)").focus();
          });
        } else {
          $option.slideUp(150);
        }
      });

      // Filter dropdown options for Created date, show/hide datepicker or input text
      var $dropdown_switcher = $(".field-switch");
      $dropdown_switcher.change(function () {
        var field_class = $(this).find("option:selected").data("field");
        var $filter_option = $(this).closest(".filter-option");
        $filter_option.find(".field").hide();
        $filter_option.find(".field." + field_class).show();

        if (field_class === "calendar") {
          $filter_option.find(".datepicker").datepicker("show");
        } else {
          $filter_option.find(".field." + field_class + " input:text").focus();
        }
      });

      // Datepicker
          $('.datepicker').datepicker({
            autoclose: true
            , orientation: 'right top'
            // , endDate: new Date()
          });

          $('#datatable-example').dataTable({
                "sPaginationType": "full_numbers",
                "iDisplayLength": 20,
          "aLengthMenu": [[20, 50, 100, -1], [20, 50, 100, "All"]]
            });

            // Bulk actions checkboxes

      var $toggle_all = $("input:checkbox.toggle-all");
      var $checkboxes = $("[name='select-product']");
      var $bulk_actions_btn = $(".bulk-actions .dropdown-toggle");

      $toggle_all.change(function () {
        var checked = $toggle_all.is(":checked");
        if (checked) {
          $checkboxes.prop("checked", "checked");
          toggleBulkActions(true);
        } else {
          $checkboxes.prop("checked", "");
          toggleBulkActions(false);
        }
      });

      $checkboxes.change(function () {
        var anyChecked = $("[name='select-product']:checked");
        toggleBulkActions(anyChecked.length);
      });

      function toggleBulkActions(show) {
        if (show) {
          $bulk_actions_btn.removeClass("disabled");
        } else {
          $bulk_actions_btn.addClass("disabled"); 
        }
      }
    });
  </script>
  
  @endsection
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
					List of Combos
				</div>&nbsp;&nbsp;

        <a href="{{URL::route('admin.new_combo')}}" class="new-user btn btn-primary btn-xs">
          Add New </a>
			</div>

			<div class="content-wrapper clearfix">
          @include('admin.partials.flash')
				<div class="results" style="width: 100%;">

					

					
					<table id="datatable-example">
	                    <thead>
	                        <tr>
	                        		<th tabindex="0" rowspan="1" colspan="1">Id
	                            </th>
	                            <th tabindex="0" rowspan="1" colspan="1">Combo
	                            </th>
	                            <th tabindex="0" rowspan="1" colspan="1">Name
	                            </th>
                                <th tabindex="0" rowspan="1" colspan="1">Status
                                </th>
	                            <th tabindex="0" rowspan="1" colspan="1">Price
	                            </th>
	                            <th tabindex="0" rowspan="1" colspan="1">Offer Price
	                            </th>
                                <th tabindex="0" rowspan="1" colspan="1">Type
                                </th>
	                            <th tabindex="0" rowspan="1" colspan="1">Serves
	                            </th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                    @foreach($items as $item)
	                        <tr>
	                        <td>{{$item->itemable->id}}</td>
	                            <td style="background-image:url({{ $item->small_thumb_image_url }});  background-repeat: no-repeat; background-position: center;background-size: contain; height:110px" >
	                            </td>
	                            <td><a href="{{URL::route('admin.update_combo',$item->id)}}">{{$item->itemable->name}}</a></td>
                                <td>@if($item->itemable->status==1)<label class="label label-success"> Active @elseif($item->itemable->status==2) <label class="label label-info"> Coming Soon  @else <label class="label label-danger"> Disabled @endif</label>
                                </td>
	                            <td class="center">{{$item->itemable->original_price}}</td>
	                            <td class="center">{{$item->itemable->discount_price}}</td>
                                <td class="center"><span class="label @if($item->itemable->type==1) label-success @else label-danger @endif">{{$item->itemable->foodType->name}}</span></td>
                                <td class="center">{{$item->itemable->serves}}</td>
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
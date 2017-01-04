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
                    Update Order 
                </div>
            </div>
            <div id="dashboard">
                      <div class="metrics clearfix">
                          <div class="metric">
                              <span class="field">Order No.</span>
                              <span class="data" id="total-item">{{$order->order_no}}</span>
                          </div>
                        <div class="metric">
                          <span class="field">Items</span>
                          <span class="data" id="total-item">{{$item_count}}</span>
                        </div>
                        <div class="metric">
                          <span class="field">Order Amount</span>
                          <span class="data" id="total-order-amount">INR {{$order->invoice->where('charge_for','Order Amount')->first()->amount}}</span>
                        </div>
                        <div class="metric">
                          <span class="field">Total Tax</span>
                          <span class="data" id="total-amount">INR {{$order->invoice->where('charge_for','Service Tax')->first()->amount+$order->invoice->where('charge_for','Service Charge')->first()->amount+$order->invoice->where('charge_for','Delivery Charge')->first()->amount+$order->invoice->where('charge_for','Packaging Charge')->first()->amount+$order->invoice->where('charge_for','VAT')->first()->amount}}
                          </span>
                        </div>
                          <div class="metric">
                              <span class="field">Discount</span>
                              <span class="data" id="total-item">INR {{$order->invoice->where('charge_for','Discount')->first()->amount}}</span>
                          </div>
                          <div class="metric">
                              <span class="field">Bueno Credits Used</span>
                              <span class="data" id="total-order-amount">INR {{$order->invoice->where('charge_for','Points Redeemed')->first()->amount}}</span>
                          </div>
                          <div class="metric">
                              <span class="field">Donation Amount</span>
                          <span class="data" id="total-amount">INR {{$order->invoice->where('charge_for','Donation Amount')->first()->amount}}
                          </span>
                          </div>
                          <div class="metric">
                              <span class="field">Total Payable Amount</span>
                              <span class="data">INR {{$order->invoice->where('charge_for','Total Amount')->first()->amount}}</span>
                          </div>
                    </div>
            <div class="content-wrapper">

                @include('admin.partials.flash')

                <form id="update-order" class="form-horizontal" method="post" action="#" role="form">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div class="form-group label-text">
                        <label class="col-sm-2 col-md-2 control-label">Full name</label>
                        <div class="col-sm-10 col-md-8">
                         {{$order->user->full_name}}
                        </div>
                    </div>
                    @if($order->user->email)
                    <div class="form-group label-text">
                        <label class="col-sm-2 col-md-2 control-label">Email address</label>
                        <div class="col-sm-10 col-md-8">
                            {{$order->user->email}}
                        </div>
                    </div>
                    @endif
                    <div class="form-group label-text">
                        <label class="col-sm-2 col-md-2 control-label">Contact number</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                {{$order->user->phone}}
                            </div>
                        </div>
                    </div>
                    <div class="address">
                        <div class="form-group label-text">
                            <label class="col-sm-2 col-md-2 control-label">Address</label>
                            <div class="col-sm-10 col-md-8">
                                {{$order->delivery_address}}
                            </div>
                        </div>
                   <div class="form-group label-text">
                        <label class="col-sm-2 col-md-2 control-label">Location</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="">
                                {{$order->area->name}}
                            </div>
                        </div>
                    </div>
                    <div class="form-group label-text">
                        <label class="col-sm-2 col-md-2 control-label">Kitchen</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="">
                                {{$order->kitchen->name}}
                            </div>
                        </div>
                    </div>
                    @if($order->status==4)
                        <div class="form-group label-text">
                            <label class="col-sm-2 col-md-2 control-label">Travel Distance</label>
                            <div class="col-sm-10 col-md-8">
                                <div class="">
                                    @if($order->travel_distance) {{($order->travel_distance / 1000)}} Kms @endif
                                    @if($order->computed_travel_distance) [Google - {{($order->computed_travel_distance / 1000)}} Kms] @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group label-text">
                            <label class="col-sm-2 col-md-2 control-label">Total Time</label>
                            <div class="col-sm-10 col-md-8">
                                <div class="">
                                    @if($order->total_delivery_time) @if($order->total_delivery_time > 60)<span class="label label-danger">{{ceil($order->total_delivery_time)}}</span> @else {{ceil($order->total_delivery_time)}}@endif Mins @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group label-text">
                            <label class="col-sm-2 col-md-2 control-label">Rider Time</label>
                            <div class="col-sm-10 col-md-8">
                                <div class="">
                                    @if($order->rider_delivery_time) @if($order->rider_delivery_time > 45)<span class="label label-danger">{{ceil($order->rider_delivery_time)}}</span> @else {{ceil($order->rider_delivery_time)}}@endif Mins @endif
                                    @if($order->computed_rider_delivery_time) [Google - {{($order->computed_rider_delivery_time)}} Mins] @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group label-text">
                            <label class="col-sm-2 col-md-2 control-label">Feedback</label>
                            <div class="col-sm-10 col-md-8">
                                <div class="">
                                    {{ $order->feedback_on_missed_call }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group label-text">
                            <label class="col-sm-2 col-md-2 control-label">Cashback credited</label>
                            <div class="col-sm-10 col-md-8">
                                <div class="">
                                    {{ $order->paymentInfo->cashback_credited }}
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($order->status==3)
                        <div class="form-group label-text">
                            <label class="col-sm-2 col-md-2 control-label">Cashback to be credited</label>
                            <div class="col-sm-10 col-md-8">
                                <div class="">
                                    {{ $order->paymentInfo->cashback_buff }}
                                </div>
                            </div>
                        </div>
                    @endif
            @if($order->meals->count()!=0)
                <div class="form-group label-text">

              <label class="col-sm-2 col-md-2 control-label">Meal(s) in Order</label>
              <div class="col-sm-10 col-md-8">


                      <?php
                      $meal_count = 0;
                      ?>
                      @foreach($order->meals as $item)
                        <div class="input select add-new-combo-meal"><label >Meal</label>
                            {{$item->itemable->name}} X {{$item->pivot->quantity}}
                        <br>
                        </div>
                      @endforeach
                      <input type="hidden" class="meal-count" value="{{$meal_count}}">
                  </div>
            </div>
            @endif

            @if($combos->count()!=0 && $order->combos->count()!=0)
             <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Combo(s) in Order</label>
              <div class="col-sm-10 col-md-8">

                      <?php
                      $combo_count = 0;
                      ?>
                      @foreach($order->combos as $item)

                        <div class="input select add-new-combo-meal"><label for="OrderMealId1">Combo</label>
                        {{$item->itemable->name}} X {{$item->pivot->quantity}}
                        <br>
                        </div>
                      @endforeach
                      <input type="hidden" class="combo-count" value="0">
                  </div>
            </div>
            @endif
            @if($order->goodies->count()!=0)
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Goody with this Order</label>
                    <div class="col-sm-10 col-md-8">

                        <?php
                        $combo_count = 0;
                        ?>
                        @foreach($order->goodies as $item)
                            <div class="input select add-new-combo-meal"><label >Goody</label>
                                <select  disabled="">
                                        <option>{{$item->itemable->name}}</option>
                                </select>
                                <label >Quantity</label>
                                <input name="combos[{{$combo_count++}}][quantity]" class="qty_dp"
                                       value="{{$item->pivot->quantity}}"
                                       type="number" min="0"  disabled=""/>
                                <br>
                            </div>
                        @endforeach
                        <input type="hidden" class="combo-count" value="0">
                    </div>
                </div>
            @endif
                     <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Payment Mode</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="payment_mode_id" class="form-control editable-attribute" id="OrderPaymentMode" disabled="">
                                <option value="{{$order->payment_mode_id}}">{{$order->paymentMode->name}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Payment Status</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="payment_status" class="form-control" @if($order->paymentInfo->status==1) disabled="" @endif >
                                    @foreach($payment_status as $p_status)
                                    <option value="{{$p_status->id}}" @if($p_status->id == $order->paymentInfo->status) selected="" @endif>{{$p_status->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Source</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="source_id" class="form-control" disabled="">
                                <option value="{{$order->source_id}}" selected="">{{$order->source->name}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @if($order->resource_order_no)
                    <div class="form-group label-text">
                        <label class="col-sm-2 col-md-2 control-label">Resource Order No</label>
                        <div class="col-sm-10 col-md-8">
                            {{$order->resource_order_no}}
                        </div>
                    </div>
                    @endif
                    @if($order->instruction)
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Special Instructions</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control" name="instruction" disabled="" value="{{$order->instruction}}"/>
                        </div>
                    </div>
                    @endif
                    @if($order->coupon)
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Coupon Code</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control" name="coupon_code" @if($order->coupon!=null) value="{{$order->coupon->code}}" @endif disabled=""/>
                        </div>
                    </div>
                    @endif
                    @if($order->deliveryBoy)
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Delivery Boy</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control" name="coupon_code" @if($order->deliveryBoy!=null) value="{{$order->deliveryBoy->full_name}}" @endif disabled=""/>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Order Status</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="status" class="form-control order-status">
                                <option value="{{$order->status}}"selected >{{$order->statusText->name}}</option>
                                    @if($order->status==7)
                                        <option value="3" >Dispatched</option>
                                    @endif
                                    @if($order->status==3)
                                        <option value="4" >Delivered</option>
                                    @endif
                                    @if($order->status!=6)
                                        <option value="6" >Cancelled</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group order-cancel-reason" @if($order->status!=6)style="display:none;"@endif id="order-cancel-reason">
                        <label class="col-sm-2 col-md-2 control-label">Cancel Reason</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="cancel_reason_id" class="form-control order-cancel-reason-option" @if($order->status==6) disabled @endif>
                                @foreach($cancel_reasons as $reason)
                                <option value="{{$reason->id}}" @if($order->cancel_reason_id == $reason->id) selected="" @endif>{{$reason->reason}}</option>
                                @endforeach
                                </select>
                            </div>
                            @if($order->status!=6)
                                <div class="div">
                                    <a href="#" class="add-cancel-reason" data-toggle="modal" data-target="#cancelReasonModelAdd">Add new</a>&nbsp;&nbsp;
                                    <a href="#" class="edit-cancel-reason" data-toggle="modal" data-target="#cancelReasonModelEdit">Edit</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group cancel-other-reason-comment" style="display:none;" >
                        <label class="col-sm-2 col-md-2 control-label">Cancel Reason Comment</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" class="form-control" name="cancel_reason_comment" value=""/>
                        </div>
                    </div>
                    @if($ngos->count()!=0)
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Donation</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="ngo_id" class="form-control ngo-id editable-attribute" disabled >
                                <option value="0" @if($order->ngo_id==0) selected @endif >No</option>
                                @foreach($ngos as $ngo)
                                    <option value="{{$ngo->id}}" @if($order->ngo_id==$ngo->id) selected @endif >{{$ngo->name}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group ngo-donation" @if($order->ngo_id==0)style="display:none;" @endif>
                        <label class="col-sm-2 col-md-2 control-label">Donation amount</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" class="form-control donation-amount" name="donation_amount" value="{{$order->donation_amount}}"disabled/>
                        </div>
                    </div>

                    @endif

                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Comment</label>
                        <div class="col-sm-10 col-md-8">
                          <textarea class="form-control" name="comment" required=""></textarea> 
                        </div>
                    </div>
                    <input type="hidden" class="edit-order-confirm" name="order_can_edit" value="0">

                    <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                            <a href="{{URL::route('admin.orders')}}" class="btn btn-default">Back</a>
                            <button type="submit" class="btn btn-success">Update Order</button>
                            @if($order->status==5 && $order->paymentInfo->status == 0 && false)
                                <a href="#" class="btn btn-danger order-edit">Edit</a>
                                <a href="#" class="btn btn-primary calculate-order-button">Calculate</a>
                            @endif
                            <a href="{{URL::route('admin.orders.kot.get',$order->id)}}" class="btn btn-primary print-kot-button" target="_blank">Print KOT</a>
                            <a href="{{URL::route('admin.orders.invoice.get',$order->id)}}" class="btn btn-primary print-kot-button" target="_blank">Print Invoice</a>
                        </div>
                    </div>
                </form>
                    

      
           

            <div class="content-wrapper" style="padding-left:1%;">
                
                <table id="datatable-order-log">
                    <thead>
                        <tr>
                            <th tabindex="0" rowspan="1" colspan="1">Updated By
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Date
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Status From
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Status To
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Comment
                            </th>
                        </tr>
                    </thead>
                    
                    
                    <tbody>

                    @foreach($orderLogs as $log)
                        <tr>
                            <td>
                                <a href="{{URL::route('admin.update_user',$log->user_id)}}">{{$log->user->full_name}}</a>
                            </td>
                            <td>{{$log->created_at->format('d,F Y H:i:s')}}</td>
                            <td class=""><span
                            @if($log->status_from_id==1)
                            class="label label-info"
                            @elseif($log->status_from_id==2)
                            class="label label-warning"
                            @elseif($log->status_from_id==3)
                            class="label label-primary"
                            @elseif($log->status_from_id==4)
                            class="label label-success"
                            @else
                            class="label label-danger"
                            @endif
                            >{{$log->statusFrom->name}}</span></td>
                            <td class=""><span
                             @if($log->status_to_id==1)
                            class="label label-info"

                            @elseif($log->status_to_id==2)
                            class="label label-warning"
                            @elseif($log->status_to_id==3)
                            class="label label-primary"
                            @elseif($log->status_to_id==4)
                            class="label label-success"
                            @else
                            class="label label-danger"
                            @endif>{{$log->statusTo->name}}</span></td>
                            <td>{{$log->comment}}</td>
                        </tr>
                    @endforeach
                        
                    </tbody>
                </table>

            </div>
        
        </div>

  @endsection

  @section('script')

  <script type="text/javascript">

      $(function () {
          Messenger.options = {
              extraClasses: 'messenger-fixed messenger-on-bottom messenger-on-right',
              theme: 'flat'
          }
      });

  $('.ngo-id').change(function(){
      if($(this).val()==0)
      {
          $('.ngo-donation').hide();
      }
      else
      {
          $('.ngo-donation').show();
      }
  });
  $('.cancel-reason').select2();
  $('.order-status').change(function(){
      if($(this).val()==6)
      {
          $('.order-cancel-reason').show();
      }
      else
      {
          $('.order-cancel-reason').hide();
      }
  });
  $("#edit_order").click(function(event){
   event.preventDefault();
   $('input,select').prop("disabled", false); // Element(s) are now enabled.
        });

      $('.order-cancel-reason-option').change(function(){
          var option = $(this).val();
          if(option == 3)
          {
              $('.cancel-other-reason-comment').show();
              $('.cancel-other-reason-comment input').attr("required", "true");
          }
          else
          {
              $('.cancel-other-reason-comment').hide();
              $('.cancel-other-reason-comment input').attr("required", "false");
          }
      });

        $(function () {
            $('.user-area').select2();

            $('.user-address').select2({
                tags : true
            });
            // form validation
            $('#update-order').validate({
                rules: {
                    "customer[first_name]": {
                        required: true
                    },
                    "customer[email]": {
                        required: true,
                        email: true
                    },
                    "customer[address]": {
                        required: true
                    },
                    "customer[notes]": {
                        required: true
                    }
                },
                highlight: function (element) {
                    $(element).closest('.form-group').removeClass('success').addClass('error');
                },
                success: function (element) {
                    element.addClass('valid').closest('.form-group').removeClass('error').addClass('success');
                }
            });
            $('#datatable-order-log').dataTable({
                "sPaginationType": "full_numbers",
                "iDisplayLength": 20,
                "aLengthMenu": [[20, 50, 100, -1], [20, 50, 100, "All"]],
                "aaSorting": [[1, 'asc']]
            });

           
        });

     $(document).ready(function(e){
         $('.edit-cancel-reason').click(function(e){
             modal = $('#cancelReasonModelEdit');
             form = modal.find('form');

             cancel_select = $('#order-cancel-reason select');
             modal.find('.cancel-reason').val(cancel_select.find('option:selected').text());
             modal.find('.cancel-id').val(cancel_select.find('option:selected').val());
         });

         $('#cancelReasonModelEdit form').submit(function(e){
             e.preventDefault();
             form = $(this);
             $.ajax({
                 url : form.attr('action'),
                 data : form.serialize(),
                 type : 'PATCH'
             }).done(function(response){
                 cancel_select = $('#order-cancel-reason select');
                 cancel_select = $('#order-cancel-reason select').find('option:selected').text(response.reason);
                 $('#cancelReasonModelEdit').modal('hide');
             });
         });

         $('#cancelReasonModelAdd form').submit(function(e){
             e.preventDefault();
             form = $(this);
             $.ajax({
                 url : form.attr('action'),
                 data : form.serialize(),
                 type : 'POST'
             }).done(function(response){
                 cancel_select = $('#order-cancel-reason select');
                 cancel_select.append($('<option>', {
                     value: response.id,
                     text: response.reason
                 }));
                 cancel_select.val(response.id);
                 $('#cancelReasonModelAdd').modal('hide');
             });
         });
     });
  </script>

      <!-- cancel reason model -->

        <div class="modal fade" id="cancelReasonModelAdd" tabindex="-1" role="dialog" aria-labelledby="cancelReasonModelAdd">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Create Reason</h4>
                    </div>
                    <form action="{{ route('admin.cancel-reasons.post') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-sm-2 col-md-2 control-label">Reason</label>
                                <div class="col-sm-10 col-md-8">
                                    <input type="text" class="form-control"  name="reason" required=""/>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn button-submit btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

                    <div class="modal fade" id="cancelReasonModelEdit" tabindex="-1" role="dialog" aria-labelledby="cancelReasonModelEdit">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="{{ route('admin.cancel-reasons.patch') }}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Cancel Reason</h4>
                                    </div>

                                    {{ method_field('PATCH') }}
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="col-sm-2 col-md-2 control-label">Reason</label>
                                            <div class="col-sm-10 col-md-8">
                                                <input type="hidden" class="cancel-id" name="id"/>
                                                <input type="text" class="form-control cancel-reason" required="" name="reason"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn button-submit btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


  @endsection
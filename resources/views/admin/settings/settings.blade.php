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
          Settings
        </div>
        <div class="pull-right" >
          <div class="page-title" style="margin-right:15px;" >Master Switch  </div> <input type="checkbox" id="master-switch" name="my-checkbox" @if($setting['master_switch']=='1.00') checked @endif>
        </div>
      </div>
      <div class="content-wrapper">
          @include('admin.partials.errors')
          @include('admin.partials.flash')
        <form id="new-product" class="form-horizontal" method="post" role="form">
                    {{ csrf_field() }}
            <legend>Main Settings</legend>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Kitchen Open-Close Time</label>
              <div class="col-sm-10 col-md-8" id="timings-tables">
                  <table class="table table-bordered">
                      <thead>
                      <tr>
                          <th colspan="2">Time Slots [ <a href="#" class="add-slot" >add slot</a> ]</th>
                          <th></th>
                      </tr>
                      </thead>
                      <tbody>
                      @if(old('timing')!=null)
                      @foreach(old('timing') as $key => $value)
                          <tr>
                              <td>
                                  <input type="text" value="{{$value['from']}}" style="width:100%" name="timing[{{ $key }}][from]" placeholder="from" class="datetimepicker time_from" required>
                              </td>
                              <td>
                                  <input type="text" value="{{$value['to']}}" style="width:100%" name="timing[{{ $key }}][to]" placeholder="to" class="datetimepicker time_to" required>
                              </td>
                              <td><a href="#" class="remove-slot-row">X</a></td>
                          </tr>
                      @endforeach
                      @else
                      @foreach($timings as $timing)
                          <tr>
                              <td>
                                  <input type="text" value="{{$timing->from}}" style="width:100%" name="timing[{{ $timing->id }}][from]" placeholder="from" class="datetimepicker time_from" required>
                              </td>
                              <td>
                                  <input type="text" value="{{$timing->to}}" style="width:100%" name="timing[{{ $timing->id }}][to]" placeholder="to" class="datetimepicker time_to" required>
                              </td>
                              <td><a href="#" class="remove-slot-row">X</a></td>
                          </tr>
                      @endforeach
                      @endif
                      <input type="hidden" @if(old('timing_count')!=null) value="timing_count" @else value="{{ $timings->count() }}" @endif name="timing_count" >
                      </tbody>
                  </table>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Refresh Rate(minutes)</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="refresh_rate" @if(old('refresh_rate')!=null) value="{{old('refresh_rate')}}" @else value="{{$setting['refresh_rate']}}" @endif required=""/>
              </div>
            </div>
            <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Operational Days</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="days[]" multiple="multiple" class="GroupGroup form-control" required="">
                                @foreach($days as $day)
                                <option value="{{$day->id}}" @if(old('days')!=null) @if(in_array($day->id,old('days'))) selected="" @endif  @elseif($day->status=='1') selected="" @endif>{{$day->day}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Invoice Message</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="invoice_message" @if(old('invoice_message')!=null) value="{{old('invoice_message')}}" @else value="{{$setting['invoice_message']}}" @endif/>
                </div>
            </div>
            <input type="hidden" class="form-control master-switch-value" name="master_switch" @if(old('master_switch')!=null) value="{{old('master_switch')}}" @else value="{{$setting['master_switch']}}" @endif required=""/>

            <legend>Payment Gateways</legend>
             <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="payment_mode[1]" value="1" @if(old('payment_mode[1]')) checked="" @elseif($payment_mode['payU']==1) checked  @endif/>Pay U Money Wallet
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="payment_mode[2]" value="1" @if(old('payment_mode[2]')) checked="" @elseif($payment_mode['cod']==1) checked  @endif/>COD
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="payment_mode[3]" value="1" @if(old('payment_mode[3]')) checked="" @elseif($payment_mode['mobikwik']==1) checked  @endif/>Mobikwik
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="payment_mode[5]" value="1" @if(old('payment_mode[5]')) checked="" @elseif($payment_mode['payTm']==1) checked  @endif/>Paytm
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="payment_mode[8]" value="1" @if(old('payment_mode[8]')) checked="" @elseif($payment_mode['razor']==1) checked  @endif/>Razor Pay
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="payment_mode[7]" value="1" @if(old('payment_mode[7]')) checked="" @elseif($payment_mode['ebs']==1) checked  @endif/>EBS
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group form-actions">
              <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                <a href="{{URL::route('admin.home')}}" class="btn btn-default">Back</a>
                  <button type="submit" class="btn btn-success">Update </button>
              </div>
            </div>
        </form>
      </div>
    </div>

  @endsection

  @section('script')

  <script type="text/javascript">
    $(function () {

      $("[name='my-checkbox']").bootstrapSwitch();



    });


$("[name='my-checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {

    if(state)// true | false
    {
        $('.master-switch-value').val(1);
    }
    else
    {
        $('.master-switch-value').val(0);
    }
});
$('.GroupGroup').select2();

    jQuery('.datetimepicker').datetimepicker({
        datepicker:false,
        format:'H:i',
        scrollInput:false,
        step: 30
    });

    $('#timings-tables').on('click', 'a[href=#]', function(event) {
        event.preventDefault();
    });

    $(document).on('click', 'a.add-slot', function(e) {
        e.preventDefault();
        var count = parseInt($(this).parents('table').find('input[type=hidden]').val())+1;
        $(this).parents('table').find('input[type=hidden]').val(count);
        add_slot_tr =  '<tr>';
        add_slot_tr += '<td>';
        add_slot_tr += '<input type="text" style="width:100%" name="timing['+count+'][from]" placeholder="from" class="datetimepicker time_from" required>';
        add_slot_tr += '</td>';
        add_slot_tr += '<td>';
        add_slot_tr += '<input type="text" style="width:100%" name="timing['+count+'][to]" placeholder="to" class="datetimepicker time_to" required>';
        add_slot_tr += '</td>';
        add_slot_tr += '<td><a href="#" class="remove-slot-row">X</a></td>';
        add_slot_tr += '</tr>';
        $(this).parents('thead').next('tbody').append(add_slot_tr);
        jQuery('.datetimepicker').datetimepicker({
            datepicker:false,
            format:'H:i',
            scrollInput:false,
            step: 30
        });
    });

    $(document).on('click','a.remove-slot-row',function(e){
        e.preventDefault();
        $(this).parent().parent().remove();
    });




    $(document).on('keyup','.time_from,.time_to',function(e){
        e.preventDefault();
    });

    $(document).on('keydown','.time_from,.time_to',function(e){
        e.preventDefault();
    });



  </script>

  @endsection
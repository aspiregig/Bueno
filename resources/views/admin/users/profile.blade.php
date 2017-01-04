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
                    {{$user->full_name}} Profile
                </div>
                
            </div>

            <div class="content-wrapper clearfix">
                <div class="profile-info">
                    <div class="avatar">
                        <img src="{{route('photo.user',$user->avatar_url)}}"/>
                        <div class="name">{{$user->full_name}}</div>
                        <div class="position">{{$user->group->name}}</div>
                    </div>
                    <div class="main-details clearfix">
                        <div class="col">
                            <div class="value">{{$user->total_orders}}</div>
                            Orders
                        </div>
                        <div class="col">
                            <div class="value">INR {{$user->total_money_spent}}</div>
                            Lifetime spent
                        </div>
                    </div>
                    <div class="details">
                      @if($user->email)
                        <div class="field">
                            <label>Email</label>
                            <div class="value">{{$user->email}}</div>
                        </div>
                      @endif
                        <div class="field">
                            <label>Referral Code</label>
                            <div class="value">{{$user->referral_code}}</div>
                        </div>
                        <div class="field">
                            <label>Phone</label>
                            <div class="value">{{$user->phone}}</div>
                        </div>
                        <?php $address_counter = 1;?>
                        @foreach($user->addresses as $address)
                        <div class="field">
                            <label>Address {{$address_counter++}} : {{$address->address_name}} </label>
                            <div class="value">
                            {{$address->address . "  " .$address->area->name}} ,{{$address->area->city->name}} {{$address->area->city->state->name}}
                            </div>
                        </div>
                        @endforeach
                        <div class="field">
                            <label>Signed up</label>
                            <div class="value">
                                {{$user->created_at->format('F d, Y')}}
                            </div>
                        </div>
                    </div>
                     <div class="main-details clearfix">
                        <div class="col" style="border-top: 1px solid rgb(223, 226, 233);">
                            <div class="value">{{$user->total_refferals}}</div>
                            Total Referral
                        </div>
                        <div class="col" style="border-top: 1px solid rgb(223, 226, 233);">
                            <div class="value">@if($user->points){{$user->points}}@else 0 @endif</div>
                            Bueno Credits
                        </div>
                              <div id="membership" class="col btn" style="color:#{{$user->membership->text_color}};background:#{{$user->membership->bg_color}};padding-left:10px;width:100%;">{{$user->membership->name}}</div>
                    </div>
                </div>

                <div class="profile-content">
                    <div class="tabs">
                        <ul>
                            <li>
                                <a href="#" class="active">User Details</a>
                            </li>
                            <li>
                                <a href="#">Orders</a>
                            </li>
                            <li>
                              <a href="#">Cart</a>
                            </li>
                            
                        </ul>
                    </div>

                    <div class="tab-content">
                        <div class="tab user-detail active">
                @include('admin.partials.errors')
                            @include('admin.partials.flash')
                <div class="content-wrapper">
                  <form id="new-customer" class="form-horizontal" method="post" role="form" enctype="multipart/form-data">
                    {{csrf_field()}}
                    {{ method_field('PATCH') }}
                    <input type="hidden" name="id" value="{{$user->id}}"> 
                      <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">First name</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control" name="first_name" @if(old('first_name')!=null) value="{{old('first_name')}}" @else value="{{$user->first_name}}" @endif required="" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Last name</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control" name="last_name" @if(old('last_name')!=null) value="{{old('last_name')}}" @else value="{{$user->last_name}}" @endif/>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Display picture</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="file" name="avatar_url" accept="image/*" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Email address</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" class="form-control" name="email" @if(old('email')!=null) value="{{old('email')}}" @else value="{{$user->email}}" @endif />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="password" class="form-control" name="password" value=""/>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Confirm Password</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="password" class="form-control" name="confirm_password" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Phone number</label>
                        <div class="col-sm-10 col-md-8">
                          <div class="has-feedback">
                          <input type="text" class="form-control mask-phone" name="phone" @if(old('phone')!=null) value="{{old('phone')}}" @else value="{{$user->phone}}" @endif required="" />
                        </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Group</label>
                        <div class="col-sm-10 col-md-8">
                          <div class="has-feedback">
                          <select name="group_id" class="form-control" id="GroupGroup">
                          @foreach($groups as $group)
                          <option value="{{$group->id}}" @if(old('group_id')==$group->id)@elseif($user->group_id == $group->id) selected="" @endif >{{$group->name}}</option>
                          @endforeach
                          </select>
                        </div>
                        </div>
                      </div>
                      <div class="form-group label-text">
                    <label class="col-sm-2 col-md-2 control-label" for="email_notify">
                        Email(Promotional)
                    </label>
                    <div class="col-sm-10 col-md-8">
                    <input type="checkbox" id="email_notify" name="email_notify" value="1" @if(old('email_notify')==1) checked="" @elseif($user->email_notify) checked="" @endif/>
                    </div>
                  </div>
                  <div class="form-group label-text">
                    <label class="col-sm-2 col-md-2 control-label" for="sms_notify">
                       Mobile(Promotional)
                    </label>
                    <div class="col-sm-10 col-md-8">
                    <input type="checkbox" id="sms_notify" name="sms_notify" value="1" @if(old('sms_notify')==1) checked="" @elseif($user->sms_notify) checked="" @endif/> 
                    </div>
                  </div>
                  @if(auth()->user()->group->name=="Admin")
                      <div class="form-group">
                          <label class="col-sm-2 col-md-2 control-label">Credits</label>
                          <div class="col-sm-10 col-md-8">
                              <div class="has-feedback">
                                  <input type="text" class="form-control" name="points" @if(old('points')!=null) value="{{old('points')}}" @else value="{{$user->points}}" @endif required="" />
                              </div>
                          </div>
                      </div>
                  @endif
                      <div class="form-group">
                          <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Status </label>
                          <div class="col-sm-10 col-md-8">
                              <select class="form-control GroupGroup"  name="status" >
                                  <option value="1" @if(old('status')==1)   selected @elseif($user->status==1)  selected="" @endif>Active</option>
                                  <option value="0" @if(old('status')===0)  selected @elseif($user->status==0)  selected="" @endif>Disabled</option>
                              </select>
                          </div>
                      </div>
                      <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                          <a href="{{URL::route('admin.users')}}" class="btn btn-default">Back</a>
                            <button class="btn btn-success">Update Details</button>
                        </div>
                      </div>
                  </form>
                </div>
           </div>
              <div class="tab order">
        <div id="datatables">
          <div class="content-wrapper">
              <table id="datatable-orders">
                  <thead>
                      <tr>
                          <th tabindex="0" rowspan="1" colspan="1">Order No
                          </th>
                          <th tabindex="0" rowspan="1" colspan="1">Date
                          </th>
                          <th tabindex="0" rowspan="1" colspan="1">Total
                          </th>
                          <th tabindex="0" rowspan="1" colspan="1">Status
                          </th>
                          <th tabindex="0" rowspan="1" colspan="1">Acquisition
                          </th>
                      </tr>
                  </thead>
                   <tfoot>
                        <tr>
                            <th rowspan="1" colspan="1">Order No</th>
                            <th rowspan="1" colspan="1">Date</th>
                            <th rowspan="1" colspan="1">Total</th>
                            <th rowspan="1" colspan="1">Status</th>
                            <th rowspan="1" colspan="1">Acquisition</th>
                        </tr>
                    </tfoot>
                  <tbody>
                  @foreach($user->orders as $order)
                      <tr>
                          <td>
                              <a href="{{URL::route('admin.update_order',$order->id)}}">{{$order->order_no}}</a>
                          </td>
                          <td>
                              {{$order->created_at->format('H:i:s F d, Y')}}
                          </td>
                          
                          <td>{{$order->paymentInfo->amount}}</td>
                          <td><span class='label {{config('bueno.color_class.'.$order->status)}}'> {{$order->statusText->name}} </span>  &nbsp; <span class='label @if($order->paymentInfo->status==3) label-success' @else label-danger' @endif>{{$order->paymentInfo->paymentStatus->name}}</span></td>
                          <td>{{$order->source->name}}</td>
                      </tr>
                  @endforeach
                  </tbody>
              </table>
              </div>
              </div>
            </div>

            <div class="tab cart">
            <div class="dataTables_wrapper" role="grid">
              <table class="dataTable" aria-describedby="datatable-example_info" id="datatable-cart">
                  <thead>
                      <tr>
                          <th tabindex="0" rowspan="1" colspan="1">Item
                          </th>
                          
                          <th tabindex="0" rowspan="1" colspan="1">Quantity
                          </th>
                          <th tabindex="0" rowspan="1" colspan="1">Total (INR)
                          </th>
                      </tr>
                  </thead>
                  <tbody>
                  @foreach($user->cartItems as $cart_item)
                      <tr>
                          <td>{{$cart_item->item->itemable->name}}</td>
                          <td>{{$cart_item->quantity}}</td>
                          <td>{{$cart_item->quantity * $cart_item->item->itemable->original_price}}</td>
                      </tr>
                  @endforeach
                  </tbody>
              </table>  
              </div>
            </div>
        </div>
    </div>
</div>

  @endsection

  @section('script')

  <script type="text/javascript">
        $(function() {

          // tabs
          var $tabs = $(".tabs a");
          var $tab_contents = $(".tab-content .tab");

          $tabs.click(function (e) {
            e.preventDefault();
            var index = $tabs.index(this);

            $tabs.removeClass("active");
            $tabs.eq(index).addClass("active");

            $tab_contents.removeClass("active");
            $tab_contents.eq(index).addClass("active");
          });
          var $memberflag = 1;
          var $member = $('#membership');
          

            // cart datatable 
            $('#datatable-cart').dataTable({
                "sPaginationType": "full_numbers",
                "iDisplayLength": 20,
                "aLengthMenu": [[20, 50, 100, -1], [20, 50, 100, "All"]]
            });

            // orders datatable 
            var table = $('#datatable-orders').dataTable({
                "sPaginationType": "full_numbers",
                "iDisplayLength": 20,
          "aLengthMenu": [[20, 50, 100, -1], [20, 50, 100, "All"]]
            });

        });


    </script>

  @endsection 
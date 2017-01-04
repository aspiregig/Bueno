
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

  <!-- Content -->
        <div id="content">
            <div class="menubar">
                <div class="sidebar-toggler visible-xs">
                    <i class="ion-navicon"></i>
                </div>
                
                <div class="page-title">
                    List of User (Total - {{$users->total()}})
                </div>
        <a href="{{URL::route('admin.new_user')}}" class="new-user btn btn-primary btn-xs" style="margin-left: 10px;">
          Add New </a>
                <div class="period-select hidden-xs">
                    <form  method="post" role="form" action="{{URL::route('admin.users.export')}}">
                        {{ csrf_field() }}
                        <div class="input-daterange">
                            <div class="input-group input-group-sm">
                <span class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </span>
                                <input name="start" type="text" class="form-control start-date" value="{{$date['start']}}" required=""/>
                            </div>


                            <p class="pull-left">to</p>

                            <div class="input-group input-group-sm">
                <span class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </span>
                                <input name="end" type="text" class="form-control end-date"  value="{{$date['end']}}" required=""/>
                            </div>
                            <button type="submit" style="margin-left:10px;"class="btn btn-primary"> Export</button>
                        </div>
                    </form>
                </div>


            </div>
            <form action="">
                <input type="text" name="keyword" placeholder="Search" value="{{ request()->has('keyword') ? request()->get('keyword') : '' }}"/>
            </form>

            <div class="content-wrapper">

                @include('admin.partials.flash')

                <table id="datatable-user">
                    <thead>
                        <tr>
                            <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_users_by('id') }}">ID<i class="{{ get_sort_icon('id') }}"></i></a></th>
                            <th tabindex="0" rowspan="1" colspan="1">User
                            </th>
                            <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_users_by('name') }}">Name<i class="{{ get_sort_icon('name') }}"></i></a></th>
                            <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_users_by('group') }}">Group<i class="{{ get_sort_icon('group') }}"></i></a></th>
                            <th tabindex="0" rowspan="1" colspan="1">Total Spent
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Total Orders
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Membership
                            </th>
                            <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_users_by('last_login') }}">Last Login At<i class="{{ get_sort_icon('last_login') }}"></i></a></th>
                        </tr>
                    </thead>
                    
                    <tfoot>
                        <tr>
                            <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_users_by('id') }}">ID<i class="{{ get_sort_icon('id') }}"></i></a></th>
                            <th rowspan="1" colspan="1">User</th>
                            <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_users_by('name') }}">Name<i class="{{ get_sort_icon('name') }}"></i></a></th>
                            <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_users_by('group') }}">Group<i class="{{ get_sort_icon('group') }}"></i></a></th>
                            <th rowspan="1" colspan="1">Total Spent</th>
                            <th rowspan="1" colspan="1">Total Orders</th>
                            <th rowspan="1" colspan="1">Membership</th>
                            <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_users_by('last_login') }}">Last Login At<i class="{{ get_sort_icon('last_login') }}"></i></a></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{$user->id}}</td>
                            <td><div class='img-responsive product-img avatar' style='background-image:url({{route('photo.user',$user->avatar_url)}});'></div></td>
                            <td><a href='{{route('admin.update_user',$user->id)}}'>{{$user->full_name}}</a></td>
                            <td><label class="label {{config('bueno.color_class.'.(($user->group->id%7)+1))}}">{{$user->group->name}}</label></td>
                            <td>{{$user->total_money_spent}}</td>
                            <td>{{$user->total_orders}}</td>
                            <td><span class='label' style='color:#{{$user->membership->text_color}};background-color:#{{$user->membership->bg_color}};'>{{$user->membership->name}}</span></td>
                            <td>{{$user->last_login_at->format('d F,Y')}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! $users->appends(request()->except('page'))->render() !!}
            </div>
  @endsection

  @section('script')


  @endsection
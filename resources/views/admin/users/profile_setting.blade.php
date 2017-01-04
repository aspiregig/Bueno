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
                    John Stewart Profile
                    <small class="hidden-xs" style="font-weight:600;">All tabs have content</small>
                </div>
                
            </div>

            <div class="content-wrapper clearfix">
                <div class="profile-info">
                    <div class="avatar">
                        <img src="images/avatars/14.jpg" />
                        <div class="name">Karen Matthews</div>
                        <div class="position">Client</div>
                        <div class="social">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="main-details clearfix">
                        <div class="col">
                            <div class="value">118</div>
                            Orders
                        </div>
                        <div class="col">
                            <div class="value">$31,250.00</div>
                            Lifetime spent
                        </div>
                    </div>
                    <div class="details">
                        <div class="field">
                            <label>Email</label>
                            <div class="value">johh.stewart@gmail.com</div>
                            <div class="sub">Home</div>
                        </div>
                        <div class="field">
                            <label>Phone</label>
                            <div class="value">(01) 123-123-1234</div>
                            <div class="sub">Home</div>
                        </div>
                        <div class="field">
                            <label>Address</label>
                            <div class="value">
                                5th Avenue 345 San Francisco 55589, CA. USA.
                            </div>
                        </div>
                        <div class="field">
                            <label>Signed up</label>
                            <div class="value">
                                03 Feb, 2013 (6 months ago)
                            </div>
                        </div>
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
                            
                        </ul>
                    </div>

                    <div class="tab-content">
                        <div class="tab notes active">
                            

                            <div class="filter clearfix">
                                <h4 class="pull-left">Recent Notes</h4>
                            </div>

                            <div class="comments">
                                

                                <div class="content-wrapper">
        <form id="new-customer" class="form-horizontal" method="post" action="#" role="form">
          <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">User name</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="customer[user_name]"  value="bueno.kitchen"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">First name</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="customer[first_name]" value="Bueno"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Last name</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="customer[last_name]" value="Kitchen"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Display picture</label>
              <div class="col-sm-10 col-md-8">
                <input type="file" name="customer[display]" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Email address</label>
              <div class="col-sm-10 col-md-8">
                  <input type="text" class="form-control" name="customer[email]" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Password</label>
              <div class="col-sm-10 col-md-8">
                  <input type="password" class="form-control" name="customer[password]" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Confirm Password</label>
              <div class="col-sm-10 col-md-8">
                  <input type="password" class="form-control" name="customer[password]" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Phone number</label>
              <div class="col-sm-10 col-md-8">
                <div class="has-feedback">
                <input type="text" class="form-control mask-phone" name="customer[phone]" />
              </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Group</label>
              <div class="col-sm-10 col-md-8">
                <div class="has-feedback">
                <select name="data[Group][Group][]" style="width: 15em;" multiple="multiple" id="GroupGroup">
                <option value="1" selected="">Administrators</option>
                <option value="2">Registered users</option>
                <option value="3">Management</option>
                </select>
              </div>
              </div>
            </div>
            <div class="form-group form-actions">
              <div class="col-sm-offset-2 col-sm-10">
                <a href="form.html" class="btn btn-default">Cancel</a>
                  <button type="submit" class="btn btn-success">Update Details</button>
              </div>
            </div>
        </form>
      </div>
                               
                                
                                
                            </div>

                            
                        </div>
                        <div class="tab orders">
                            <table id="datatable-example">
                                <thead>
                                    <tr>
                                        <th tabindex="0" rowspan="1" colspan="1">Order
                                        </th>
                                        <th tabindex="0" rowspan="1" colspan="1">Date
                                        </th>
                                        <th tabindex="0" rowspan="1" colspan="1">Status
                                        </th>
                                        <th tabindex="0" rowspan="1" colspan="1">Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <a href="#">#1445</a>
                                        </td>
                                        <td>
                                            Mar 11, 11:50am

                                            <i class="ion-alert-circled" data-toggle="tooltip" title="This order is urgent to ship">
                                            </i>
                                        </td>
                                        <td><span class="label label-default">Paid</span></td>
                                        <td>$399.99</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="#">#1897</a>
                                        </td>
                                        <td>Mar 10, 06:50am</td>
                                        <td><span class="label label-warning">Pending</span></td>
                                        <td>$699.99</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="#">#1089</a>
                                        </td>
                                        <td>Mar 08, 01:43pm</td>
                                        <td><span class="label label-default">Paid</span></td>
                                        <td>$1,879.99</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="#">#1883</a>
                                        </td>
                                        <td>Mar 07, 07:30pm</td>
                                        <td><span class="label label-default">Paid</span></td>
                                        <td>$279.99</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="#">#2288</a>
                                        </td>
                                        <td>Mar 04, 04:30pm</td>
                                        <td><span class="label label-default">Paid</span></td>
                                        <td>$399.99</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="#">#3978</a>
                                        </td>
                                        <td>Mar 11, 11:50am</td>
                                        <td><span class="label label-default">Paid</span></td>
                                        <td>$399.99</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="#">#6876</a>
                                        </td>
                                        <td>Mar 04, 04:30pm</td>
                                        <td><span class="label label-default">Paid</span></td>
                                        <td>$399.99</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="#">#3445</a>
                                        </td>
                                        <td>Mar 11, 11:50am</td>
                                        <td><span class="label label-default">Paid</span></td>
                                        <td>$399.99</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="#">#3445</a>
                                        </td>
                                        <td>Mar 11, 11:50am</td>
                                        <td><span class="label label-default">Paid</span></td>
                                        <td>$399.99</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="#">#3445</a>
                                        </td>
                                        <td>Mar 11, 11:50am</td>
                                        <td><span class="label label-default">Paid</span></td>
                                        <td>$399.99</td>
                                    </tr>
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


          // orders datatable 
            $('#datatable-example').dataTable({
                "sPaginationType": "full_numbers",
                "iDisplayLength": 20,
          "aLengthMenu": [[20, 50, 100, -1], [20, 50, 100, "All"]]
            });
        });
    </script>

  @endsection 
<div id="sidebar-default" class="main-sidebar">
 <div style="background: #000;padding:30px; background-image: url('http://bueno.kitchen/assets/images/bueno_logo_white.png');background-repeat: no-repeat;background-size: 120px 40px;
    background-position: center; ">
 </div>
      <div class="current-user">
        <a href="#" class="name">
          <!-- <img class="avatar" src="images/avatars/bueno.jpg" /> -->
          <img class="avatar" src="{{route('photo.user',auth()->user()->avatar_url)}}">
          <span>
             {{  strlen(auth()->user()->full_name) > 12 ? substr(auth()->user()->full_name, 0, 12) . '...' : auth()->user()->full_name}}
            <i class="fa fa-chevron-down"></i>
          </span>
        </a>
        <ul class="menu">
          <li>
            <a href="{{URL::route('users.logout')}}">Sign Out</a>
          </li>
        </ul>
      </div>
      @if(manageGeneral(auth()->user()))
      <div class="menu-section">
        <h3>General</h3>
        <ul>
        <?php 
        // @if(manage(auth()->user(),'dashboard'))
        //   <li>
        //     <a href="{{URL::route('admin.dashboard')}}">
        //       <i class="ion-earth"></i> 
        //       <span>Dashboard</span>
        //     </a>
        //   </li>
        //   @endif
          ?>
          @if(manageUser(auth()->user()))
              <li>
                  <a href="{{URL::route('admin.users')}}" data-toggle="sidebar">
                    <i class="ion-person-stalker"></i> <span>Users</span>
                    <i class="fa fa-chevron-down"></i>
                  </a>
                  <ul class="submenu">
                    <li><a href="{{URL::route('admin.users')}}">Users list</a></li>
                    <li><a href="{{URL::route('admin.new_user')}}">Add User</a></li>
                  </ul>
                </li>
          @endif
          @if(manageOrder(auth()->user()))
              <li>
                <a href="{{URL::route('admin.orders')}}" data-toggle="sidebar">
                  <i class="ion-clipboard"></i> <span>Orders</span>
                  <i class="fa fa-chevron-down"></i>
                </a>
                <ul class="submenu">
                  <li><a href="{{URL::route('admin.orders')}}">All Orders</a></li>
                    <li><a href="{{URL::route('admin.new_orders')}}">New Orders</a></li>
                    <li><a href="{{URL::route('admin.packed_orders')}}">Packed Orders</a></li>
                    <li><a href="{{URL::route('admin.dispatched_orders')}}">Dispatched Orders</a></li>
                    <li><a href="{{URL::route('admin.cancelled_orders')}}">Cancelled Orders</a></li>
                  <li><a href="{{URL::route('admin.new_order')}}">Quick Order</a></li>
                  <li><a href="{{URL::route('admin.orders.pack.get')}}">Pack Order</a></li>
                  <li><a href="{{URL::route('admin.orders.dispatch.get')}}">Dispatch Order</a></li>
                </ul>
              </li>
          @endif
        @if(manage(auth()->user(),'reports'))
          <li>
            <a href="#" data-toggle="sidebar">
              <i class="ion-stats-bars"></i> <span>Reports</span>
              <i class="fa fa-chevron-down"></i>
            </a>
            <ul class="submenu">
              <li><a href="{{URL::route('admin.report.daily')}}">Daily Reports</a></li>
            </ul>
          </li>
        @endif
        </ul>
      </div>
      @endif
      <div class="menu-section">
        <h3>Management</h3>
        <ul>
          @if(manageMeal(auth()->user()))
          <li>
            <a href="{{URL::route('admin.meals')}}" data-toggle="sidebar">
              <i class="ion-pizza"></i> <span>Meals and Combo</span>
              <i class="fa fa-chevron-down"></i>
            </a>
            <ul class="submenu">
              <li><a href="{{URL::route('admin.meals')}}">View All Meals</a></li>
              <li><a href="{{URL::route('admin.new_meal')}}">Add New Meal</a></li>
              <li><a href="{{URL::route('admin.combos')}}">View All Combos</a></li>
              <li><a href="{{URL::route('admin.new_combo')}}">Add New Combo</a></li>
              <li><a href="{{URL::route('admin.categories')}}">View All Categories</a></li>
              <li><a href="{{URL::route('admin.new_category')}}">Add New Category</a></li>
              <li><a href="{{URL::route('admin.cuisines')}}">View All Cuisines</a></li>
              <li><a href="{{URL::route('admin.new_cuisine')}}">Add New Cuisine</a></li>
              <li><a href="{{URL::route('admin.availabilities')}}">View All Courses</a></li>
              <li><a href="{{URL::route('admin.new_availability')}}">Add New Course</a></li>
            </ul>
          </li>
          @endif
          @if(manageCoupon(auth()->user()))
          <li>
            <a href="{{URL::route('admin.coupons')}}" data-toggle="sidebar">
              <i class="ion-pricetags"></i> <span>Coupons</span>
              <i class="fa fa-chevron-down"></i>
            </a>
            <ul class="submenu">
              <li><a href="{{URL::route('admin.coupons')}}">View All Coupons</a></li>
              <li><a href="{{URL::route('admin.new_coupon')}}">Add new Coupon</a></li>
              <li><a href="{{URL::route('admin.goodies')}}">View All Goodies</a></li>
              <li><a href="{{URL::route('admin.new_goody')}}">Add new Goody</a></li>
            </ul>
          </li>
          @endif
          @if(manageLocation(auth()->user()))
          <li>
            <a href="{{URL::route('admin.cities')}}" data-toggle="sidebar">
              <i class="ion-location"></i> <span>Location</span>
              <i class="fa fa-chevron-down"></i>
            </a>
            <ul class="submenu">
              <li><a href="{{URL::route('admin.states')}}">View All States</a></li>
              <li><a href="{{URL::route('admin.new_state')}}">Add new State</a></li>
              <li><a href="{{URL::route('admin.cities')}}">View All Cities</a></li>
              <li><a href="{{URL::route('admin.new_city')}}">Add new City</a></li>
              <li><a href="{{URL::route('admin.areas')}}">View All Areas</a></li>
              <li><a href="{{URL::route('admin.new_area')}}">Add new Area</a></li>    
            </ul>
          </li>
          @endif
          @if(manageAdText(auth()->user()))
          <li>
            <a href="{{URL::route('admin.html_banners')}}" data-toggle="sidebar">
              <i class="ion-laptop"></i> <span>Banners</span>
              <i class="fa fa-chevron-down"></i>
            </a>
            <ul class="submenu">
              <li><a href="{{URL::route('admin.html_banners')}}">View All Banners</a></li>
              <li><a href="{{URL::route('admin.new_html_banner')}}">Add new Banner</a></li>
            </ul>
          </li>
          <li>
            <a href="{{URL::route('admin.ad_texts')}}" data-toggle="sidebar">
              <i class="ion-compose"></i> <span>Header Ads Text</span>
              <i class="fa fa-chevron-down"></i>
            </a>
            <ul class="submenu">
              <li><a href="{{URL::route('admin.ad_texts')}}">View All Ads Text</a></li>
              <li><a href="{{URL::route('admin.new_ad_text')}}">Add new Ad Text</a></li>
            </ul>
          </li>
          @endif
          @if(manageTestimonial(auth()->user()))
          <li>
            <a href="{{URL::route('admin.testimonials')}}" data-toggle="sidebar">
              <i class="ion-chatbox-working"></i> <span>Testimonials</span>
              <i class="fa fa-chevron-down"></i>
            </a>
            <ul class="submenu">
              <li><a href="{{URL::route('admin.testimonials')}}">View All Testimonials</a></li>
              <li><a href="{{URL::route('admin.new_testimonial')}}">Add new Testimonial</a></li>
            </ul>
          </li>
          @endif
          @if(managePage(auth()->user()))
          <li>
            <a href="{{URL::route('admin.seo_titles')}}" data-toggle="sidebar">
              <i class="ion-arrow-graph-up-right"></i> <span>SEO Titles</span>
              <i class="fa fa-chevron-down"></i>
            </a>
            <ul class="submenu">
              <li><a href="{{URL::route('admin.seo_titles')}}">View All SEO Titles</a></li>
              <li><a href="{{URL::route('admin.new_seo_title')}}">Add new SEO Title</a></li>
            </ul>
          </li>

          <li>
              <a href="{{URL::route('admin.press')}}" data-toggle="sidebar">
                  <i class="ion-image"></i> <span>Press</span>
                  <i class="fa fa-chevron-down"></i>
              </a>
              <ul class="submenu">
                  <li><a href="{{URL::route('admin.press')}}">View All Press</a></li>
                  <li><a href="{{URL::route('admin.new_press')}}">Add new Press</a></li>
              </ul>
          </li>
          @endif
          @if(manageSlider(auth()->user()))
          <li>
            <a href="{{URL::route('admin.home_sliders')}}" data-toggle="sidebar">
              <i class="ion-image"></i> <span>Home Sliders</span>
              <i class="fa fa-chevron-down"></i>
            </a>
            <ul class="submenu">
              <li><a href="{{URL::route('admin.home_sliders')}}">View All Home Silders</a></li>
              <li><a href="{{URL::route('admin.new_home_slider')}}">Add new Home Slider</a></li>
            </ul>
          </li>
          @endif
          @if(manageCareer(auth()->user()))
          <li >
            <a href="{{URL::route('admin.business')}}" >
              <i class="ion-navigate"></i> <span>Business Queries</span>
            </a>
          </li>
          @endif
          @if(manageCatering(auth()->user()))
          <li>
            <a href="{{URL::route('admin.catering')}}" >
              <i class="ion-coffee"></i> <span>Catering Leads</span>
            </a>
          </li>
          @endif
          @if(canManage(auth()->user(),"queries"))
          <li>
            <a href="{{URL::route('admin.queries')}}" >
              <i class="ion-help"></i> <span>General Queries</span>
            </a>
          </li>
          @endif
          @if(manageDeliveryBoy(auth()->user()))
          <li>
            <a href="{{URL::route('admin.delivery_boys')}}" data-toggle="sidebar">
              <i class="ion-briefcase"></i> <span>Delivery Boy</span>
              <i class="fa fa-chevron-down"></i>
            </a>
            <ul class="submenu">
              <li><a href="{{URL::route('admin.delivery_boys')}}">View All Delivery Boys</a></li>
              <li><a href="{{URL::route('admin.new_delivery_boy')}}">Add new Delivery Boy</a></li>
            </ul>
          </li>
          @endif
        </ul>
      </div>
      <div class="menu-section">
      
        <h3>Admin</h3><ul>
          @if(canManage(auth()->user(),"settings"))
          <li>
            <a href="profile-setting" data-toggle="sidebar">
              <i class="ion-person"></i> <span>My account</span>
              <i class="fa fa-chevron-down"></i>
            </a>
                  <ul class="submenu">
                    
                    <li><a href="{{URL::route('admin.settings')}}">Settings</a></li>
                    <li>
              <a href="{{URL::route('admin.buenosettings')}}" >
                Bueno Credits System
              </a>
            </li>
                    
                  </ul>
          </li> 
          @endif
          @if(canManage(auth()->user(),"ngos"))
          <li>
              <a href="{{URL::route('admin.ngos')}}" data-toggle="sidebar">
                <i class="ion-umbrella"></i> <span>NGOs</span>
                <i class="fa fa-chevron-down"></i>
              </a>
              <ul class="submenu">
                <li><a href="{{URL::route('admin.new_ngo')}}">Add New NGO</a></li>
                <li><a href="{{URL::route('admin.ngos')}}">All NGOs</a></li>
              </ul>
            </li>
            @endif
        @if(manageRole(auth()->user()))
                <li>
                  <a href="{{URL::route('admin.roles')}}" data-toggle="sidebar">
                    <i class="ion-filing"></i> <span>Roles</span>
                    <i class="fa fa-chevron-down"></i>
                  </a>
                  <ul class="submenu">
                    <li><a href="{{URL::route('admin.roles')}}">All Roles</a></li>
                  </ul>
                </li>
          @endif
          @if(manageGroup(auth()->user()))
            <li>
              <a href="{{URL::route('admin.groups')}}" data-toggle="sidebar">
                <i class="ion-person-stalker"></i> <span>Groups</span>
                <i class="fa fa-chevron-down"></i>
              </a>
              <ul class="submenu">
                <li><a href="{{URL::route('admin.new_group')}}">Add New Group</a></li>
                <li><a href="{{URL::route('admin.groups')}}">All Groups</a></li>
              </ul>
            </li>
          @endif
          @if(manageKitchen(auth()->user()))
             <li>
              <a href="{{URL::route('admin.kitchens')}}" data-toggle="sidebar">
                <i class="ion-fork"></i> <span>Kitchen</span>
                <i class="fa fa-chevron-down"></i>
              </a>
              <ul class="submenu">
                <li><a href="{{URL::route('admin.new_kitchen')}}">Add New Kitchen</a></li>
                <li><a href="{{URL::route('admin.kitchens')}}">All Kitchens</a></li>
              </ul>
            </li>
          @endif
          @if(canManage(auth()->user(),"membership"))
              <li>
                  <a href="{{URL::route('admin.memberships')}}" data-toggle="sidebar">
                      <i class="ion-heart"></i> <span>Membership</span>
                      <i class="fa fa-chevron-down"></i>
                  </a>
                  <ul class="submenu">
                      <li><a href="{{URL::route('admin.new_membership')}}">Add New Membership</a></li>
                      <li><a href="{{URL::route('admin.memberships')}}">All Memberships</a></li>
                  </ul>
              </li>
          @endif
          @if(manageRole(auth()->user()))
            
          @endif
            @if(canManage(auth()->user(),"stock"))
                <li>
                    <a href="{{URL::route('admin.stocks')}}" >
                        <i class="ion-levels"></i> <span>Stocks</span>
                    </a>
                </li>
            @endif
        </ul>
      </div>
      <div class="bottom-menu hidden-sm" style="visibility:none;">
        <ul>
          <li><a href="#"></a></li>
          <li>
            <a href="#">
              
            </a>
            
          </li>
          <li><a href="#"></i></a></li>
        </ul>
      </div>
    </div>
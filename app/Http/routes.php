<?php

use App\Events\ReferralSuccess;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('trigger-email/{email}','AuthController@emailTest');

// Route::get('/sms',function(){
//       return event(new ReferralSuccess(123,123,123));
// });
//-----------------************* ADMIN ********************-------------

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

  Route::group(['namespace' => 'Admin'], function() {
    /*
    |---------------------------------------------------------------------------
    | Location
    |---------------------------------------------------------------------------
    */

//State

    Route::get('states', ['as' => 'admin.states', 'uses' => 'StateController@index']);
    Route::get('state/{id}/edit', ['as' => 'admin.update_state', 'uses' => 'StateController@edit']);
    Route::patch('state/{id}/edit', ['as' => 'admin.update_state', 'uses' => 'StateController@update']);
    Route::get('state/{id}/delete', ['as' => 'admin.delete_state', 'uses' => 'StateController@destroy']);
    Route::get('state/new', ['as' => 'admin.new_state', 'uses' => 'StateController@create']);
    Route::post('state/new', ['as' => 'admin.new_state', 'uses' => 'StateController@store']);

//City
    Route::get('cities', ['as' => 'admin.cities', 'uses' => 'CityController@index']);
    Route::get('city/{id}/edit', ['as' => 'admin.update_city', 'uses' => 'CityController@edit']);
    Route::post('city/{id}/edit', ['as' => 'admin.update_city', 'uses' => 'CityController@update']);
    Route::get('city/{id}/delete', ['as' => 'admin.delete_city', 'uses' => 'CityController@destroy']);
    Route::get('city/new', ['as' => 'admin.new_city', 'uses' => 'CityController@create']);
    Route::post('city/new', ['as' => 'admin.new_city', 'uses' => 'CityController@store']);

//Area
    Route::get('areas', ['as' => 'admin.areas', 'uses' => 'AreaController@index']);
    Route::get('area/new', ['as' => 'admin.new_area', 'uses' => 'AreaController@create']);
    Route::post('area/new', ['as' => 'admin.new_area', 'uses' => 'AreaController@store']);
    Route::get('area/{id}/edit', ['as' => 'admin.update_area', 'uses' => 'AreaController@edit']);
    Route::patch('area/{id}/edit', ['as' => 'admin.update_area', 'uses' => 'AreaController@update']);
    Route::get('area/{id}/delete', ['as' => 'admin.delete_area', 'uses' => 'AreaController@destroy']);

    /*
    |---------------------------------------------------------------------------
    | Header Text
    |---------------------------------------------------------------------------
    */

    Route::get('ad-text/new', ['as' => 'admin.new_ad_text', 'uses' => 'AdTextController@create']);
    Route::post('ad-text/new', ['as' => 'admin.new_ad_text', 'uses' => 'AdTextController@store']);
    Route::get('ad-text/{id}/edit', ['as' => 'admin.update_ad_text', 'uses' => 'AdTextController@edit']);
    Route::post('ad-text/{id}/edit', ['as' => 'admin.update_ad_text', 'uses' => 'AdTextController@update']);
    Route::get('ad-text/{id}/delete', ['as' => 'admin.delete_ad_text', 'uses' => 'AdTextController@destroy']);
    Route::get('ad-texts', ['as' => 'admin.ad_texts', 'uses' => 'AdTextController@index']);

    /*
    |---------------------------------------------------------------------------
    | Banner
    |---------------------------------------------------------------------------
    */

    Route::get('banner/new', ['as' => 'admin.new_html_banner', 'uses' => 'BannerController@create']);
    Route::post('banner/new', ['as' => 'admin.new_html_banner', 'uses' => 'BannerController@store']);
    Route::get('banner/{id}/edit', ['as' => 'admin.update_html_banner', 'uses' => 'BannerController@edit']);
    Route::post('banner/{id}/edit', ['as' => 'admin.update_html_banner', 'uses' => 'BannerController@update']);
    Route::get('banner/{id}/delete', ['as' => 'admin.delete_html_banner', 'uses' => 'BannerController@destroy']);
    Route::get('banners', ['as' => 'admin.html_banners', 'uses' => 'BannerController@index']);


    /*
    |---------------------------------------------------------------------------
    | Testimonial
    |---------------------------------------------------------------------------
    */

    Route::get('testimonial/new', ['as' => 'admin.new_testimonial', 'uses' => 'TestimonialController@create']);
    Route::post('testimonial/new', ['as' => 'admin.new_testimonial', 'uses' => 'TestimonialController@store']);
    Route::get('testimonial/{id}/edit', ['as' => 'admin.update_testimonial', 'uses' => 'TestimonialController@edit']);
    Route::post('testimonial/{id}/edit', ['as' => 'admin.update_testimonial', 'uses' => 'TestimonialController@update']);
    Route::get('testimonial/{id}/delete', ['as' => 'admin.delete_testimonial', 'uses' => 'TestimonialController@destroy']);
    Route::get('testimonials', ['as' => 'admin.testimonials', 'uses' => 'TestimonialController@index']);

    /*
    |---------------------------------------------------------------------------
    | Pages
    |---------------------------------------------------------------------------
    */

    Route::get('seo-titles', ['as' => 'admin.seo_titles', 'uses' => 'PageController@index']);
    Route::get('seo-title/{id}/edit', ['as' => 'admin.update_seo_title', 'uses' => 'PageController@edit']);
    Route::patch('seo-title/{id}/edit', ['as' => 'admin.update_seo_title', 'uses' => 'PageController@update']);
    Route::get('seo-title/{id}/delete', ['as' => 'admin.delete_seo_title', 'uses' => 'PageController@destroy']);
    Route::get('seo-title/new', ['as' => 'admin.new_seo_title', 'uses' => 'PageController@create']);
    Route::post('seo-title/new', ['as' => 'admin.new_seo_title', 'uses' => 'PageController@store']);

    /*
    |---------------------------------------------------------------------------
    | Home - Slider
    |---------------------------------------------------------------------------
    */

    Route::get('home-sliders', ['as' => 'admin.home_sliders', 'uses' => 'HomeSliderController@index']);
    Route::get('home-slider/{id}/edit', ['as' => 'admin.update_home_slider', 'uses' => 'HomeSliderController@edit']);
    Route::post('home-slider/{id}/edit', ['as' => 'admin.update_home_slider', 'uses' => 'HomeSliderController@update']);
    Route::get('home-slider/{id}/delete', ['as' => 'admin.delete_home_slider', 'uses' => 'HomeSliderController@destroy']);
    Route::get('home-slider/new', ['as' => 'admin.new_home_slider', 'uses' => 'HomeSliderController@create']);
    Route::post('home-slider/new', ['as' => 'admin.new_home_slider', 'uses' => 'HomeSliderController@store']);


    /*
    |---------------------------------------------------------------------------
    | Leads
    |---------------------------------------------------------------------------
    */
//career

    Route::get('business-queries', ['as' => 'admin.business', 'uses' => 'LeadController@business']);
    Route::get('business-queries/{id}', ['as' => 'admin.business.view', 'uses' => 'LeadController@viewBusinessQuery']);
//catering

    Route::get('catering-leads', ['as' => 'admin.catering', 'uses' => 'LeadController@catering']);
    Route::get('catering-leads/{id}', ['as' => 'admin.catering.view', 'uses' => 'LeadController@viewCateringQuery']);
//queries
    Route::get('queries', ['as' => 'admin.queries', 'uses' => 'LeadController@queries']);

    // view Query
    Route::get('query/{id}', ['as' => 'admin.queries.view', 'uses' => 'LeadController@viewQuery']);



    /*
    |---------------------------------------------------------------------------
    | Meals , Combos , Categories , Cuisines and availability
    |---------------------------------------------------------------------------
    */

//meals

    Route::get('meals', ['as' => 'admin.meals', 'uses' => 'MealController@index']);
    Route::get('meal/new', ['as' => 'admin.new_meal', 'uses' => 'MealController@create']);
    Route::post('meal/new', ['as' => 'admin.new_meal', 'uses' => 'MealController@store']);
    Route::get('meal/{id}/edit', ['as' => 'admin.update_meal', 'uses' => 'MealController@edit']);
    Route::patch('meal/{id}/edit', ['as' => 'admin.update_meal', 'uses' => 'MealController@update']);
    Route::get('meal/{id}/delete', ['as' => 'admin.delete_meal', 'uses' => 'MealController@destroy']);

//combos

    Route::get('combos', ['as' => 'admin.combos', 'uses' => 'ComboController@index']);
    Route::get('combo/new', ['as' => 'admin.new_combo', 'uses' => 'ComboController@create']);
    Route::post('combo/new', ['as' => 'admin.new_combo', 'uses' => 'ComboController@store']);
    Route::get('combo/{id}/edit', ['as' => 'admin.update_combo', 'uses' => 'ComboController@edit']);
    Route::patch('combo/{id}/edit', ['as' => 'admin.update_combo', 'uses' => 'ComboController@update']);
    Route::get('combo/{id}/delete', ['as' => 'admin.delete_combo', 'uses' => 'ComboController@destroy']);

//Categories

    Route::get('categories', ['as' => 'admin.categories', 'uses' => 'CategoryController@index']);
    Route::get('category/new', ['as' => 'admin.new_category', 'uses' => 'CategoryController@create']);
    Route::post('category/new', ['as' => 'admin.new_category', 'uses' => 'CategoryController@store']);
    Route::get('category/{id}/edit', ['as' => 'admin.update_category', 'uses' => 'CategoryController@edit']);
    Route::patch('category/{id}/edit', ['as' => 'admin.update_category', 'uses' => 'CategoryController@update']);
    Route::get('category/{id}/delete', ['as' => 'admin.delete_category', 'uses' => 'CategoryController@destroy']);

//Cuisines

    Route::get('cuisines', ['as' => 'admin.cuisines', 'uses' => 'CuisineController@index']);
    Route::get('cuisine/new', ['as' => 'admin.new_cuisine', 'uses' => 'CuisineController@create']);
    Route::post('cuisine/new', ['as' => 'admin.new_cuisine', 'uses' => 'CuisineController@store']);
    Route::get('cuisine/{id}/edit', ['as' => 'admin.update_cuisine', 'uses' => 'CuisineController@edit']);
    Route::patch('cuisine/{id}/edit', ['as' => 'admin.update_cuisine', 'uses' => 'CuisineController@update']);
    Route::get('cuisine/{id}/delete', ['as' => 'admin.delete_cuisine', 'uses' => 'CuisineController@destroy']);

// Availability

    Route::get('courses', ['as' => 'admin.availabilities', 'uses' => 'AvailabilityController@index']);
    Route::get('course/new', ['as' => 'admin.new_availability', 'uses' => 'AvailabilityController@create']);
    Route::post('course/new', ['as' => 'admin.new_availability', 'uses' => 'AvailabilityController@store']);
    Route::get('course/{id}/edit', ['as' => 'admin.update_availability', 'uses' => 'AvailabilityController@edit']);
    Route::patch('course/{id}/edit', ['as' => 'admin.update_availability', 'uses' => 'AvailabilityController@update']);
    Route::get('course/{id}/delete', ['as' => 'admin.delete_availability', 'uses' => 'AvailabilityController@destroy']);


    /*
    |---------------------------------------------------------------------------
    | Settings & Bueno Points
    |---------------------------------------------------------------------------
    */
    Route::get('settings', ['as' => 'admin.settings', 'uses' => 'SettingsController@settings']);
    Route::post('settings', ['as' => 'admin.settings', 'uses' => 'SettingsController@update_settings']);
    Route::get('bueno-points', ['as' => 'admin.buenosettings', 'uses' => 'SettingsController@buenoSettings']);
    Route::post('bueno-points', ['as' => 'admin.buenosettings', 'uses' => 'SettingsController@buenoSettingsUpdate']);
    Route::get('setting/master_switch/', ['as' => 'admin.master_switch', 'uses' => 'SettingsController@masterSwitch']);
    Route::get('profile-setting', 'AdminController@profile_setting');

    /*
    |---------------------------------------------------------------------------
    | NGOs
    |---------------------------------------------------------------------------
    */

    Route::get('ngos', ['as' => 'admin.ngos', 'uses' => 'NgoController@index']);
    Route::get('ngo/new', ['as' => 'admin.new_ngo', 'uses' => 'NgoController@create']);
    Route::post('ngo/new', ['as' => 'admin.new_ngo', 'uses' => 'NgoController@store']);
    Route::get('ngo/{id}/edit', ['as' => 'admin.update_ngo', 'uses' => 'NgoController@edit']);
    Route::patch('ngo/{id}/edit', ['as' => 'admin.update_ngo', 'uses' => 'NgoController@update']);
    Route::get('ngo/{id}/delete', ['as' => 'admin.delete_ngo', 'uses' => 'NgoController@destroy']);


    /*
    |---------------------------------------------------------------------------
    | Users and Memberships
    |---------------------------------------------------------------------------
    */

    Route::get('users', ['as' => 'admin.users', 'uses' => 'UserController@index']);
    Route::get('user/new', ['as' => 'admin.new_user', 'uses' => 'UserController@create']);
    Route::post('user/new', ['as' => 'admin.new_user', 'uses' => 'UserController@store']);
    Route::get('user/{id}/edit', ['as' => 'admin.update_user', 'uses' => 'UserController@edit']);
    Route::patch('user/{id}/edit', ['as' => 'admin.update_user', 'uses' => 'UserController@update']);
    Route::get('/user', ['as' => 'admin.user', 'uses' => 'UserController@show']);
    Route::post('/users/export', ['as' => 'admin.users.export', 'uses' => 'UserController@export']);
    Route::get('/user/{id}/delete', ['as' => 'admin.user_delete', 'uses' => 'UserController@destroy']);
    Route::get('/user/all', ['as' => 'admin.user_all', 'uses' => 'UserController@all']);
    Route::get('/admin/all', ['as' => 'admin.user_all', 'uses' => 'UserController@allAdmin']);
    Route::get('/getallusers',['as'=> 'admin.all_users', 'uses' => 'UserController@ajaxUsers']);


    //Membership
    Route::get('memberships', ['as' => 'admin.memberships', 'uses' => 'MembershipController@index']);
    Route::get('membership/new', ['as' => 'admin.new_membership', 'uses' => 'MembershipController@create']);
    Route::post('membership/new', ['as' => 'admin.new_membership', 'uses' => 'MembershipController@store']);
    Route::get('membership/{id}/edit', ['as' => 'admin.update_membership', 'uses' => 'MembershipController@edit']);
    Route::patch('membership/{id}/edit', ['as' => 'admin.update_membership', 'uses' => 'MembershipController@update']);
    Route::get('membership/{id}/delete', ['as' => 'admin.delete_membership', 'uses' => 'MembershipController@destroy']);


    /*
    |---------------------------------------------------------------------------
    | Roles & Groups
    |---------------------------------------------------------------------------
    */

//Roles

    Route::get('roles', ['as' => 'admin.roles', 'uses' => 'RoleController@index']);
    Route::get('role/new', ['as' => 'admin.new_role', 'uses' => 'RoleController@create']);
    Route::post('role/new', ['as' => 'admin.new_role', 'uses' => 'RoleController@store']);
    Route::get('role/{id}/edit', ['as' => 'admin.update_role', 'uses' => 'RoleController@edit']);
    Route::post('role/{id}/edit', ['as' => 'admin.update_role', 'uses' => 'RoleController@update']);

//Groups

    Route::get('groups', ['as' => 'admin.groups', 'uses' => 'GroupController@index']);
    Route::get('group/new', ['as' => 'admin.new_group', 'uses' => 'GroupController@create']);
    Route::post('group/new', ['as' => 'admin.new_group', 'uses' => 'GroupController@store']);
    Route::get('group/{id}/edit', ['as' => 'admin.update_group', 'uses' => 'GroupController@edit']);
    Route::patch('group/{id}/edit', ['as' => 'admin.update_group', 'uses' => 'GroupController@update']);
    Route::get('group/{id}/delete', ['as' => 'admin.delete_group', 'uses' => 'GroupController@destroy']);

    /*
    |---------------------------------------------------------------------------
    | Kitchens
    |---------------------------------------------------------------------------
    */

    Route::get('kitchens', ['as' => 'admin.kitchens', 'uses' => 'KitchenController@index']);
    Route::get('kitchen/new', ['as' => 'admin.new_kitchen', 'uses' => 'KitchenController@create']);
    Route::post('kitchen/new', ['as' => 'admin.new_kitchen', 'uses' => 'KitchenController@store']);
    Route::get('kitchen/{id}/edit', ['as' => 'admin.update_kitchen', 'uses' => 'KitchenController@edit']);
    Route::patch('kitchen/{id}/edit', ['as' => 'admin.update_kitchen', 'uses' => 'KitchenController@update']);
    Route::get('kitchen/{id}/delete', ['as' => 'admin.delete_kitchen', 'uses' => 'KitchenController@destroy']);

    /*
    |---------------------------------------------------------------------------
    | Meal Stock
    |---------------------------------------------------------------------------
    */

    Route::get('stocks', ['as' => 'admin.stocks', 'uses' => 'StocksController@index']);
    Route::get('stocks/{id}', ['as' => 'admin.stocks.edit', 'uses' => 'StocksController@edit']);
    Route::post('stocks/{id}', ['as' => 'admin.stocks.update', 'uses' => 'StocksController@update']);
    /*
    |---------------------------------------------------------------------------
    | Orders
    |---------------------------------------------------------------------------
    */
    Route::get('orders', ['as' => 'admin.orders', 'uses' => 'OrderController@index']);
    Route::get('orders/new-orders', ['as' => 'admin.new_orders', 'uses' => 'OrderController@getNewOrders']);
    Route::get('orders/cancelled-orders', ['as' => 'admin.cancelled_orders', 'uses' => 'OrderController@getCancelledOrders']);
    Route::get('orders/mark-as-settled', ['as' => 'admin.orders.mark_as_settled', 'uses' => 'OrderController@getMarkOrdersAsSettled']);
    Route::get('orders/packed-orders', ['as' => 'admin.packed_orders', 'uses' => 'OrderController@getPackedOrders']);
    Route::get('orders/dispatched-orders', ['as' => 'admin.dispatched_orders', 'uses' => 'OrderController@getDispatchedOrders']);
    Route::get('order/new', ['as' => 'admin.new_order', 'uses' => 'OrderController@create']);
    Route::post('order/new', ['as' => 'admin.new_order', 'uses' => 'OrderController@store']);
    Route::get('order/{id}/edit', ['as' => 'admin.update_order', 'uses' => 'OrderController@edit']);
    Route::get('order/{id}/kot', ['as' => 'admin.orders.kot.get', 'uses' => 'OrderController@getKot']);
    Route::get('order/{id}/invoice', ['as' => 'admin.orders.invoice.get', 'uses' => 'OrderController@getInvoice']);
    Route::patch('order/{id}/edit', ['as' => 'admin.update_order', 'uses' => 'OrderController@update']);
    Route::get('order/apply-coupon', ['as' => 'admin.orders.apply_coupon', 'uses' => 'OrderController@applyCoupon']);
    Route::get('order/dispatch', ['as' => 'admin.orders.dispatch.get', 'uses' => 'OrderController@getDispatch']);
    Route::post('order/dispatch', ['as' => 'admin.orders.dispatch.post', 'uses' => 'OrderController@postDispatch']);
    Route::get('order/pack', ['as' => 'admin.orders.pack.get', 'uses' => 'OrderController@getPack']);
    Route::post('order/pack', ['as' => 'admin.orders.pack.post', 'uses' => 'OrderController@postPack']);
    Route::get('/getallorders',['as'=> 'admin.all_orders', 'uses' => 'OrderController@ajaxOrders']);
    Route::post('/orders/export', ['as' => 'admin.orders.export', 'uses' => 'OrderController@export']);


    /*
    |---------------------------------------------------------------------------
    | Delivery Boys
    |---------------------------------------------------------------------------
    */
    Route::get('delivery-boys', ['as' => 'admin.delivery_boys', 'uses' => 'DeliveryBoyController@index']);
    Route::get('delivery-boy/new', ['as' => 'admin.new_delivery_boy', 'uses' => 'DeliveryBoyController@create']);
    Route::post('delivery-boy/new', ['as' => 'admin.new_delivery_boy', 'uses' => 'DeliveryBoyController@store']);
    Route::get('delivery-boy/{id}/edit', ['as' => 'admin.update_delivery_boy', 'uses' => 'DeliveryBoyController@edit']);
    Route::patch('delivery-boy/{id}/edit', ['as' => 'admin.update_delivery_boy', 'uses' => 'DeliveryBoyController@update']);
    Route::get('delivery-boy/{id}/delete', ['as' => 'admin.delete_delivery_boy', 'uses' => 'DeliveryBoyController@destroy']);


    /*
    |---------------------------------------------------------------------------
    | Cart
    |---------------------------------------------------------------------------
    */

    /*
    |---------------------------------------------------------------------------
    | Payments
    |---------------------------------------------------------------------------
    */
    Route::get('payment-gateway', 'AdminController@payment_gateway');//#

    /*
    |---------------------------------------------------------------------------
    | Coupons
    |---------------------------------------------------------------------------
    */
    Route::get('coupons', ['as' => 'admin.coupons', 'uses' => 'CouponController@index']);
    Route::get('coupon/new', ['as' => 'admin.new_coupon', 'uses' => 'CouponController@create']);
    Route::post('coupon/new', ['as' => 'admin.new_coupon', 'uses' => 'CouponController@store']);
    Route::get('coupon/{id}/edit', ['as' => 'admin.update_coupon', 'uses' => 'CouponController@edit']);
    Route::patch('coupon/{id}/edit', ['as' => 'admin.update_coupon', 'uses' => 'CouponController@update']);
    Route::get('coupon/{id}/delete', ['as' => 'admin.delete_coupon', 'uses' => 'CouponController@destroy']);

    //Goodies
    Route::get('goodies', ['as' => 'admin.goodies', 'uses' => 'GoodyController@index']);
    Route::get('goody/new', ['as' => 'admin.new_goody', 'uses' => 'GoodyController@create']);
    Route::post('goody/new', ['as' => 'admin.new_goody', 'uses' => 'GoodyController@store']);
    Route::get('goody/{id}/edit', ['as' => 'admin.update_goody', 'uses' => 'GoodyController@edit']);
    Route::patch('goody/{id}/edit', ['as' => 'admin.update_goody', 'uses' => 'GoodyController@update']);

    /*
    |---------------------------------------------------------------------------
    | Reports
    |---------------------------------------------------------------------------
    */
    Route::get('report/gm', ['as' => 'admin.report.gm', 'uses' => 'ReportController@gm']);
    Route::get('report/stock', ['as' => 'admin.report.stock', 'uses' => 'ReportController@stock']);
    Route::get('report/stock/export', ['as' => 'admin.stock.export', 'uses' => 'ReportController@downloadStockReport']);
    Route::get('report/gm/export', ['as' => 'admin.gm.export', 'uses' => 'ReportController@downloadGmReport']);
    Route::get('report/customer', ['as' => 'admin.report.customer', 'uses' => 'ReportController@customer']);
    Route::get('report/customer/export', ['as' => 'admin.customer.export', 'uses' => 'ReportController@downloadCustomerReport']);
    Route::get('report/promo', ['as' => 'admin.report.promo', 'uses' => 'ReportController@promo']);
    Route::get('report/daily', ['as' => 'admin.report.daily', 'uses' => 'ReportController@displayReport']);
    Route::post('report/daily', ['as' => 'admin.report.daily.post', 'uses' => 'ReportController@displayReport']);

    Route::get('/',['as' => 'admin.home' ,'uses' => 'AdminController@home']);
    Route::get('dashboard', ['as' => 'admin.dashboard', 'uses' => 'AdminController@index']);//#
    Route::post('dashboard', 'AdminController@dashboard');//#
    Route::get('admin/admin', 'AdminController@set_admin_session');
    Route::get('admin/manager', 'AdminController@set_manager_session');
    Route::get('reviews', 'AdminController@reviews');//#
    Route::get('logout', 'AdminController@logout');

    Route::get('user/email/{email}',['as'=>'admin.user.get_by_email','uses'=>'UserController@getByEmail']);
    Route::get('user/phone/{phone}',['as'=>'admin.user.get_by_phone','uses'=>'UserController@getByphone']);


    /*
    |---------------------------------------------------------------------------
    | Press
    |---------------------------------------------------------------------------
    */
    Route::get('press', ['as' => 'admin.press', 'uses' => 'PressController@index']);
    Route::get('press/new', ['as' => 'admin.new_press', 'uses' => 'PressController@create']);
    Route::post('press/new', ['as' => 'admin.new_press', 'uses' => 'PressController@store']);
    Route::get('press/{id}/edit', ['as' => 'admin.update_press', 'uses' => 'PressController@edit']);
    Route::patch('press/{id}/edit', ['as' => 'admin.update_press', 'uses' => 'PressController@update']);
    Route::get('press/{id}/delete', ['as' => 'admin.delete_press', 'uses' => 'PressController@destroy']);

    /*
   |---------------------------------------------------------------------------
   | Cancel Reasons
   |---------------------------------------------------------------------------
   */
    Route::post('cancel-reasons', ['as' => 'admin.cancel-reasons.post', 'uses' => 'OrderController@createCancelReason']);
    Route::patch('cancel-reasons', ['as' => 'admin.cancel-reasons.patch', 'uses' => 'OrderController@editCancelReason']);
  });
});

/*
|---------------------------------------------------------------------------
| Photos
|---------------------------------------------------------------------------
*/

Route::get('/photo/meal/{path}/{size?}',['as'=>'photo.meals','uses'=>'PhotoController@getMealPhoto']);
Route::get('/photo/thumbnail-meal/{path}/{size?}',['as'=>'photo.meals.thumb','uses'=>'PhotoController@getMealThumbPhoto']);
Route::get('/photo/combo/{path}/{size?}',['as'=>'photo.combos','uses'=>'PhotoController@getComboPhoto']);
Route::get('/photo/thumbnail-combo/{path}/{size?}',['as'=>'photo.combos.thumb','uses'=>'PhotoController@getComboThumbPhoto']);
Route::get('/photo/goody/{path}',['as'=>'photo.goody','uses'=>'PhotoController@getGoodyPhoto']);

Route::get('/photo/web-page/{path}',['as'=>'photo.web_page','uses'=>'PhotoController@getPagePhoto']);
Route::get('/photo/home-slider/{path}',['as'=>'photo.home_slider','uses'=>'PhotoController@getSliderPhoto']);
Route::get('/photo/user/{path}',['as'=>'photo.user','uses'=>'PhotoController@getUserPhoto']);
Route::get('/photo/user',['uses'=>'PhotoController@getUserPhoto']);

Route::get('/photo/banner/{path}',['as'=>'photo.banner','uses'=>'PhotoController@getBannerPhoto']);
Route::get('/photo/item/{path}',['as'=>'photo.item','uses'=>'PhotoController@getItemPhoto']);
Route::get('/photo/ngo/{path}',['as'=>'photo.ngos','uses'=>'PhotoController@getNgoPhoto']);


/*
|---------------------------------------------------------------------------
| Front End
|---------------------------------------------------------------------------
*/

// pages
Route::get('/', ['as' => 'pages.index', 'uses' => 'PagesController@index']);
Route::get('/sitemap.xml', ['as' => 'pages.sitemap', 'uses' => 'PagesController@sitemap']);
Route::get('/catering', ['as' => 'pages.catering.get', 'uses' => 'PagesController@getCatering']);
Route::get('/faq', ['as' => 'pages.faq.get', 'uses' => 'PagesController@getFaq']);
Route::get('/about', ['as' => 'pages.about.get', 'uses' => 'PagesController@getAbout']);
Route::get('/press', ['as' => 'pages.press.get', 'uses' => 'PagesController@getPress']);
Route::get('/privacy-policy', ['as' => 'pages.privacy_policy.get', 'uses' => 'PagesController@getPrivacyPolicy']);
Route::get('/terms-conditions', ['as' => 'pages.terms_conditions.get', 'uses' => 'PagesController@getTermsConditions']);
Route::get('/refund-cancellation', ['as' => 'pages.refund_cancellation.get', 'uses' => 'PagesController@getRefundCancellation']);
Route::get('/catering/enquiry', ['as' => 'pages.catering.enquiry.get', 'uses' => 'PagesController@getCateringEnquiry']);
Route::get('/catering/download', ['as' => 'pages.catering.download.get', 'uses' => 'PagesController@getCateringDownload']);
Route::get('/corporate-orders', ['as' => 'pages.corporate_orders.get', 'uses' => 'PagesController@getCorporateOrders']);
Route::get('/careers', ['as' => 'pages.careers.get', 'uses' => 'PagesController@getCareers']);
Route::get('/contact-us', ['as' => 'pages.contact.get', 'uses' => 'PagesController@getContact']);
Route::post('/contact-us', ['as' => 'pages.contact.post', 'uses' => 'PagesController@postContact']);
Route::get('/business-query', ['as' => 'pages.business.get', 'uses' => 'PagesController@getBusinessQuery']);
Route::post('/business-query', ['as' => 'pages.business.post', 'uses' => 'PagesController@postBusinessQuery']);
Route::get('/feedback', ['as' => 'pages.feedback.get', 'uses' => 'PagesController@getFeedback']);
Route::get('/catering/query', ['as' => 'pages.catering.query.get', 'uses' => 'PagesController@getCateringQuery']);
Route::post('/catering/query', ['as' => 'pages.catering.query.post', 'uses' => 'PagesController@postCateringQuery']);
Route::get('/areas', ['as' => 'pages.areas.query.get', 'uses' => 'PagesController@getAreas']);

Route::get('/opt-out-sms', ['as' => 'pages.otp_out.sms.get', 'uses' => 'PagesController@getOptOutSMS']);
Route::post('/opt-out-sms', ['as' => 'pages.otp_out.sms.post', 'uses' => 'PagesController@postOptOutSMS']);

// auth - users
Route::get('/login', ['as' => 'users.login.get', 'uses' => 'AuthController@getLogin']);
Route::post('/login', ['as' => 'users.login.post', 'uses' => 'AuthController@postLogin']);
Route::get('/login/{auth}', ['as' => 'users.login.social', 'uses' => 'AuthController@social']);
Route::get('/logout', ['as' => 'users.logout', 'uses' => 'AuthController@logout']);
Route::get('/register', ['as' => 'users.register.get', 'uses' => 'AuthController@getRegister']);
Route::post('/register', ['as' => 'users.register.post', 'uses' => 'AuthController@postRegister']);
Route::get('/verify/', ['as' => 'users.verify.get', 'uses' => 'AuthController@getVerify']);
Route::post('/verify/', ['as' => 'users.verify.post', 'uses' => 'AuthController@postVerify']);
Route::get('/resend/', ['as' => 'users.resend.get', 'uses' => 'AuthController@getResend']);
Route::get('/forgot-password', ['as' => 'users.forgot_password.get', 'uses' => 'AuthController@getForgotPassword']);
Route::post('/forgot-password', ['as' => 'users.forgot_password.post', 'uses' => 'AuthController@postForgotPassword']);
Route::get('/reset-password', ['as' => 'users.reset_password.get', 'uses' => 'AuthController@getResetPassword']);
Route::post('/reset-password', ['as' => 'users.reset_password.post', 'uses' => 'AuthController@postResetPassword']);
Route::post('/resend-forgot-password', ['as' => 'users.resend_forgot_password.post', 'uses' => 'AuthController@resendForgotPasswordOTP']);

// users
Route::get('/account', ['as' => 'users.account.get', 'uses' => 'UsersController@getAccount']);
Route::get('/account/edit', ['as' => 'users.account.edit.get', 'uses' => 'UsersController@getEditAccount']);
Route::patch('/account/edit', ['as' => 'users.account.edit.post', 'uses' => 'UsersController@postEditAccount']);
Route::patch('/account/password/edit', ['as' => 'users.account.password.post', 'uses' => 'UsersController@postEditPassword']);
Route::get('/bueno-loyalty', ['as' => 'users.loyalty.get', 'uses' => 'UsersController@getBuenoLoyalty']);
Route::post('/bueno-loyalty', ['as' => 'users.loyalty.post', 'uses' => 'UsersController@postBuenoLoyalty']);
Route::patch('/account', ['as' => 'users.account.patch', 'uses' => 'UsersController@postAccount']);
Route::get('/new-address', ['as' => 'users.address.new.get', 'uses' => 'UsersController@getNewAddress']);
Route::post('/new-address', ['as' => 'users.address.new.post', 'uses' => 'UsersController@postNewAddress']);
Route::get('/edit-address/{id}', ['as' => 'users.address.edit.get', 'uses' => 'UsersController@editAddress']);
Route::post('/edit-address/{id}', ['as' => 'users.address.edit.post', 'uses' => 'UsersController@updateAddress']);
Route::get('/edit-address/{id}/delete', ['as' => 'users.address.edit.delete', 'uses' => 'UsersController@deleteAddress']);
Route::get('/saved-items', ['as' => 'users.saved_items.get', 'uses' => 'UsersController@getUserSavedItems']);
Route::post('/saved-items', ['as' => 'users.saved_items.post', 'uses' => 'UsersController@postUserSavedItems']);
Route::post('/saved-items/cart', ['as' => 'users.saved_items.cart.post', 'uses' => 'UsersController@addAllSavedToCart']);
Route::delete('/saved-items', ['as' => 'users.saved_items.delete', 'uses' => 'UsersController@deleteUserSavedItems']);
Route::get('/orders', ['as' => 'users.orders.get', 'uses' => 'UsersController@getUserOrders']);
Route::get('/orders/{id}', ['as' => 'users.orders.single.get', 'uses' => 'UsersController@getUserSingleOrder']);
Route::get('/cart', ['as' => 'users.cart.get', 'uses' => 'CartController@getCart']);
Route::post('/cart', ['as' => 'users.cart.post', 'uses' => 'CartController@addToCart']);
Route::delete('/cart', ['as' => 'users.cart.delete', 'uses' => 'CartController@removeFromCart']);
Route::post('/cart/quantity', ['as' => 'users.cart.quantity.post', 'uses' => 'CartController@saveCartQuantity']);
Route::get('/order-feedback', ['as' => 'users.order.feedback', 'uses' => 'UsersController@getOrderFeedback']);

// sesssion address
Route::post('/area', ['as' => 'users.area.post', 'uses' => 'UsersController@postArea']);

// checkouts
Route::get('/checkout', ['as' => 'checkout.get', 'uses' => 'CheckoutController@getCheckout']);
Route::post('/checkout', ['as' => 'checkout.post', 'uses' => 'CheckoutController@postCheckout']);
Route::get('/checkout/{payment_mode_id}/success', ['as' => 'checkout.success.get', 'uses' => 'CheckoutController@postSuccess']);
Route::post('/checkout/{payment_mode_id}/success', ['as' => 'checkout.success.post', 'uses' => 'CheckoutController@postSuccess']);
Route::post('/checkout/{payment_mode_id}/failure', ['as' => 'checkout.failure.post', 'uses' => 'CheckoutController@postFailure']);
Route::post('/checkout/{payment_mode_id}/charge', ['as' => 'checkout.charge.post', 'uses' => 'CheckoutController@postCharge']);
Route::get('/checkout/apply-coupon', ['as' => 'checkout.coupon.apply', 'uses' => 'CheckoutController@applyCoupon']);

// items
Route::get('/global-menu', ['as' => 'items.search.xprs-menu.get', 'uses' => 'ItemsController@getXprsMenu']);
Route::post('/global-menu/area', ['as' => 'items.search.xprs-menu.area.post', 'uses' => 'ItemsController@postXprsMenuArea']);
Route::get('/global-menu/{slug}', ['as' => 'items.xprs-menu.single.get', 'uses' => 'ItemsController@getXprsMenuSingle']);
Route::get('/todays-specials', ['as' => 'items.hot_deals.get', 'uses' => 'ItemsController@getHotDeals']);
Route::post('/hot-deals/area', ['as' => 'items.search.hot_deals.area.post', 'uses' => 'ItemsController@postHotDealsArea']);
Route::get('/hot-deals/{slug}', ['as' => 'items.hot_deals.single.get', 'uses' => 'ItemsController@getHotDealsSingle']);

// offers
Route::get('/offers', ['as' => 'pages.offers.get', 'uses' => 'PagesController@getOffers']);

//Jooleh
Route::post('/webapi/jooleh-delivery/uname={uname}',['as'=>'jooleh.webhook.post','uses' => 'APIController@joolehNotification']);

 Route::get("/authake/user/login" , function(){ return Redirect::to("login", 301); });
     Route::get("/carts", function(){ return Redirect::to("cart", 301); });
     Route::get("/category/Beverages", function(){ return Redirect::to("/global-menu", 301); });
     Route::get("/meal/carabbien coffee", function(){ return Redirect::to("global-menu/carabbien-coffee", 301); });
     Route::get("/meal/Passion Fruit - Mojito", function(){ return Redirect::to("global-menu/passion-fruit-mojito", 301); });
     Route::get("/meal/Lemon Ice Tea", function(){ return Redirect::to("global-menu/lemon-ice-tea", 301); });
     Route::get("/meal/Carrot & Celery Juice", function(){ return Redirect::to("global-menu/carrot-celery-juice", 301); });
     Route::get("/category/Chips-Dips", function(){ return Redirect::to("/global-menu", 301); });
     Route::get("/meal/pita-chips-with-hummus", function(){ return Redirect::to("global-menu/pita-chips-with-hummus", 301); });
     Route::get("/meal/Lavash with Labneh", function(){ return Redirect::to("global-menu/lavash-with-labneh", 301); });
     Route::get("/meal/nachos-with-fresh-salsa", function(){ return Redirect::to("global-menu/nachos-with-fresh-salsa", 301); });
     Route::get("/meal/Herb Roasted Young Potatoes with Aioli", function(){ return Redirect::to("global-menu/herb-roasted-young-potatoes-with-aioli", 301); });
     Route::get("/category/Snacks-Salads", function(){ return Redirect::to("/global-menu", 301); });
     Route::get("/meal/Garlic Bread", function(){ return Redirect::to("global-menu/garlic-bread", 301); });
     Route::get("/meal/Chilli Cheese Toast", function(){ return Redirect::to("global-menu/chilli-cheese-toast", 301); });
     Route::get("/meal/Sriracha Paneer", function(){ return Redirect::to("global-menu/sriracha-paneer", 301); });
     Route::get("/meal/Sriracha Chicken", function(){ return Redirect::to("global-menu/sriracha-chicken", 301); });
     Route::get("/meal/Chicken Seekh", function(){ return Redirect::to("global-menu/chicken-seekh", 301); });
     Route::get("/meal/Mutton Seekh", function(){ return Redirect::to("global-menu/mutton-seekh", 301); });
     Route::get("/meal/Greek Salad", function(){ return Redirect::to("global-menu/greek-salad", 301); });
     Route::get("/meal/Ceaser Salad", function(){ return Redirect::to("global-menu/ceaser-salad", 301); });
     Route::get("/category/Burgers-Wraps-Panini", function(){ return Redirect::to("/global-menu", 301); });
     Route::get("/meal/Veg Farmhouse Burger", function(){ return Redirect::to("global-menu/veg-farmhouse-burger", 301); });
     Route::get("/meal/Tandoori Paneer", function(){ return Redirect::to("global-menu/tandoori-paneer", 301); });
     Route::get("/meal/chicken-seekh-burger", function(){ return Redirect::to("global-menu/chicken-seekh-burger", 301); });
     Route::get("/meal/mutton-seekh-burger", function(){ return Redirect::to("global-menu/mutton-seekh-burger", 301); });
     Route::get("/meal/Paneer Shawarma", function(){ return Redirect::to("global-menu/paneer-shawarma", 301); });
     Route::get("/meal/Veg Burrito", function(){ return Redirect::to("global-menu/veg-burrito", 301); });
     Route::get("/meal/chicken shawarma", function(){ return Redirect::to("global-menu/chicken-shawarma", 301); });
     Route::get("/meal/Chicken Burrito", function(){ return Redirect::to("global-menu/chicken-burrito", 301); });
     Route::get("/meal/Triple Pepper", function(){ return Redirect::to("global-menu/triple-pepper", 301); });
     Route::get("/meal/Smoke Veg Panini", function(){ return Redirect::to("global-menu/smoke-veg-panini", 301); });
     Route::get("/meal/BBQ Chicken Panini", function(){ return Redirect::to("global-menu/bbq-chicken-panini", 301); });
     Route::get("/meal/Ham & Cheese Panini", function(){ return Redirect::to("global-menu/ham-cheese-panini", 301); });
     Route::get("/category/Pastas-Pizzas", function(){ return Redirect::to("/global-menu", 301); });
     Route::get("/meal/penne-arrabiata-veg-with-garlic-bread", function(){ return Redirect::to("global-menu/penne-arrabiata-veg-with-garlic-bread", 301); });
     Route::get("/meal/Penne Primavera Veg with Garlic Bread", function(){ return Redirect::to("global-menu/penne-primavera-veg-with-garlic-bread", 301); });
     Route::get("/meal/VEG LASAGNA", function(){ return Redirect::to("global-menu/veg-lasagna", 301); });
     Route::get("/meal/Penne Arrabiata Chicken with Garlic Bread", function(){ return Redirect::to("global-menu/penne-arrabiata-chicken-with-garlic-bread", 301); });
     Route::get("/meal/penne-primavera-chicken-with-garlic-bread", function(){ return Redirect::to("global-menu/penne-primavera-chicken-with-garlic-bread", 301); });
     Route::get("/meal/Chicken Lasagna", function(){ return Redirect::to("global-menu/chicken-lasagna", 301); });
     Route::get("/meal/Margarita", function(){ return Redirect::to("global-menu/margarita", 301); });
     Route::get("/meal/Four Seasons Pizza", function(){ return Redirect::to("global-menu/four-seasons-pizza", 301); });
     Route::get("/meal/Chicken Tikka & Onion", function(){ return Redirect::to("global-menu/chicken-tikka-onion", 301); });
     Route::get("/meal/Paprika Chicken & Mushroom Pizza", function(){ return Redirect::to("global-menu/paprika-chicken-mushroom-pizza", 301); });
     Route::get("/category/Global-Mains", function(){ return Redirect::to("/global-menu", 301); });
     Route::get("/meal/Cottage Cheese Steak with BBQ Sauce", function(){ return Redirect::to("global-menu/cottage-cheese-steak-with-bbq-sauce", 301); });
     Route::get("/meal/chinese-greens-in-manchurian-sauce-with-fried-rice", function(){ return Redirect::to("global-menu/chinese-greens-in-manchurian-sauce-with-fried-rice", 301); });
     Route::get("/meal/thai-green-curry-with-steamed-rice", function(){ return Redirect::to("global-menu/thai-green-curry-with-steamed-rice", 301); });
     Route::get("/meal/Grilled Chicken with BBQ Sauce", function(){ return Redirect::to("global-menu/grilled-chicken-with-bbq-sauce", 301); });
     Route::get("/meal/shredded-chicken-in-hot-garlic-sauce-with-veg-fried-rice", function(){ return Redirect::to("global-menu/shredded-chicken-in-hot-garlic-sauce-with-veg-fried-rice", 301); });
     Route::get("/meal/thai-red-curry-chicken-with-steamed-rice", function(){ return Redirect::to("global-menu/thai-red-curry-chicken-with-steamed-rice", 301); });
     Route::get("/category/Desserts", function(){ return Redirect::to("/global-menu", 301); });
     Route::get("/meal/Linzer Cookies (5 Pieces)", function(){ return Redirect::to("global-menu/sweetgingerhoneycookies", 301); });
     Route::get("/meal/tiramisu", function(){ return Redirect::to("global-menu/tiramisu", 301); });
     Route::get("/meal/Blueberry Cheesecake", function(){ return Redirect::to("global-menu/blueberry-cheesecake", 301); });
     Route::get("/meal/magnificient", function(){ return Redirect::to("global-menu/magnificient", 301); });
     Route::get("/meal/Orange & Choclate Delight", function(){ return Redirect::to("global-menu/orange-choclate-delight", 301); });
     Route::get("/meal/chocolate-brownie", function(){ return Redirect::to("global-menu/chocolate-brownie", 301); });
     Route::get("/category/Subcontinental-Mains", function(){ return Redirect::to("/global-menu", 301); });
     Route::get("/meal/Bueno Special Veg Biryani with Salan", function(){ return Redirect::to("global-menu/bueno-special-veg-biryani-with-salan", 301); });
     Route::get("/meal/Dal Peshawari with Rumali Roti", function(){ return Redirect::to("global-menu/dal-peshawari-with-rumali-roti", 301); });
     Route::get("/meal/paneer-tawa-masala-with-roomali-roti", function(){ return Redirect::to("global-menu/paneer-tawa-masala-with-roomali-roti", 301); });
     Route::get("/meal/Paneer Makhni with Jeera Rice", function(){ return Redirect::to("global-menu/paneer-makhni-with-jeera-rice", 301); });
     Route::get("/meal/Bueno Special Chicken Biryani with Salan", function(){ return Redirect::to("global-menu/bueno-special-chicken-biryani-with-salan", 301); });
     Route::get("/meal/Chicken Tikka Tawa Masala", function(){ return Redirect::to("global-menu/chicken-tikka-tawa-masala", 301); });
     Route::get("/meal/Murg Makhni with Jeera Rice", function(){ return Redirect::to("global-menu/murg-makhni-with-jeera-rice", 301); });
     Route::get("/meal/Mutton Korma", function(){ return Redirect::to("global-menu/mutton-korma", 301); });
     Route::get("/pages/client_speak", function(){ return Redirect::to("/", 301); });
     Route::get("/pages/careers", function(){ return Redirect::to("careers", 301); });
     Route::get("/pages/faqs", function(){ return Redirect::to("faq", 301); });
     Route::get("/pages/privacy_policy", function(){ return Redirect::to("privacy-policy", 301); });
     Route::get("/pages/terms_and_conditions", function(){ return Redirect::to("terms-conditions", 301); });
     Route::get("/pages/refund_and_cancellation", function(){ return Redirect::to("refund-cancellation", 301); });
     Route::get("/pages/contact_us", function(){ return Redirect::to("contact-us", 301); });
     Route::get("/lost-password", function(){ return Redirect::to("forgot-password", 301); });
     Route::get("/categories/index/Beverages", function(){ return Redirect::to("/global-menu", 301); });
     Route::get("/categories/index/Chips-Dips", function(){ return Redirect::to("/global-menu", 301); });
     Route::get("/categories/index/Snacks-Salads", function(){ return Redirect::to("/global-menu", 301); });
     Route::get("/categories/index/Burgers-Wraps-Panini", function(){ return Redirect::to("/global-menu", 301); });
     Route::get("/categories/index/Pastas-Pizzas", function(){ return Redirect::to("/global-menu", 301); });
     Route::get("/categories/index/Global-Mains", function(){ return Redirect::to("/global-menu", 301); });
     Route::get("/categories/index/Desserts", function(){ return Redirect::to("/global-menu", 301); });
     Route::get("/categories/index/Subcontinental-Mains", function(){ return Redirect::to("/global-menu", 301); });
     Route::get("/meal/Pesto Vegetables with Herb & Garlic Rice", function(){ return Redirect::to("global-menu/pesto-vegetables-with-herb-garlic-rice", 301); });
     Route::get("/pages/client_speak/page:2/sort:Testimonial.id/direction:DESC", function(){ return Redirect::to("/global-menu", 301); });
     Route::get("/pages/client_speak/sort:Testimonial.id/direction:desc", function(){ return Redirect::to("/", 301); });
     Route::get("/pages/client_speak/sort:Testimonial.id/direction:desc/page:2", function(){ return Redirect::to("/", 301); });


Route::group(['prefix' => 'webapi'], function () {
  Route::group(['namespace' => 'OldApi'], function() {

    Route::get('/', ['as' => 'api.index.get', 'uses' => 'ApiController@getIndex']);
    Route::post('/', ['as' => 'api.index.post', 'uses' => 'ApiController@getIndex']);

    Route::get('/offers.json', ['as' => 'api.offers.get', 'uses' => 'ApiController@getOffers']);
    Route::post('/offers.json', ['as' => 'api.offers.post', 'uses' => 'ApiController@getOffers']);

    Route::get('/data.json', ['as' => 'api.data.get', 'uses' => 'ApiController@getData']);

    Route::get('/services.php', ['as' => 'api.services.get', 'uses' => 'ApiController@getService']);
    Route::post('/services.php', ['as' => 'api.services.post', 'uses' => 'ApiController@getService']);

    Route::get('/order.php', ['as' => 'api.order.get', 'uses' => 'ApiController@getOrder']);
    Route::post('/order.php', ['as' => 'api.order.post', 'uses' => 'ApiController@getOrder']);

    Route::post('/index.php', ['as' => 'api.order.create.get', 'uses' => 'ApiController@createOrder']);
    Route::get('/index.php', ['as' => 'api.order.create.post', 'uses' => 'ApiController@createOrder']);

    Route::post('/payment.php/{payment_mode_id}/checksum', ['as' => 'api.payment.checksum.get', 'uses' => 'ApiController@buildChecksum']);
    Route::get('/payment.php/{payment_mode_id}/checksum', ['as' => 'api.payment.checksum.post', 'uses' => 'ApiController@buildChecksum']);

    Route::post('/payment.php/{payment_mode_id}/verify', ['as' => 'api.payment.verify_checksum.get', 'uses' => 'ApiController@verifyChecksum']);
    Route::get('/payment.php/{payment_mode_id}/verify', ['as' => 'api.payment.verify_checksum.post', 'uses' => 'ApiController@verifyChecksum']);

  });

});

  Route::group(['prefix' => 'api/v1'], function () {
    Route::group(['namespace' => 'Api'], function() {

      // pages
      Route::get('/config', ['as' => 'api.v1.index.get', 'uses' => 'PageController@index']);

      // Authenticate User
      Route::post('/authenticate', ['as' => 'api.v1.authenticate.post', 'uses' => 'AuthController@authenticate']);
      Route::post('/register', ['as' => 'api.v1.register.post', 'uses' => 'AuthController@postRegister']);
      Route::post('/forgot-password', ['as' => 'api.v1.forgot_password.post', 'uses' => 'AuthController@postForgotPassword']);
      Route::post('/reset-password', ['as' => 'api.v1.reset_password.post', 'uses' => 'AuthController@postResetPassword']);
      Route::post('/resend-otp', ['as' => 'api.v1.resend_otp.post', 'uses' => 'AuthController@postResendOTP']);

      // areas
      Route::get('/areas', ['as' => 'api.v1.areas.get', 'uses' => 'LocationController@getAreas']);

      // user
      Route::get('/authenticate', ['as' => 'api.v1.authenticate.get', 'uses' => 'UserController@getUser']);
      // user - orders
      Route::get('/authenticate/orders', ['as' => 'api.v1.authenticate.orders.get', 'uses' => 'UserController@getOrders']);
      Route::get('/authenticate/orders/{id}', ['as' => 'api.v1.authenticate.orders.single.get', 'uses' => 'UserController@getOrder']);
      // user - saved items
      Route::get('/authenticate/saved-items', ['as' => 'api.v1.authenticate.saved_items.get', 'uses' => 'UserController@getSavedItems']);
      Route::post('/authenticate/saved-items', ['as' => 'api.v1.authenticate.saved_items.post', 'uses' => 'UserController@postSavedItems']);
      Route::delete('/authenticate/saved-items', ['as' => 'api.v1.authenticate.saved_items.delete', 'uses' => 'UserController@deleteSavedItems']);
      // user - addresses
      Route::get('/authenticate/addresses', ['as' => 'api.v1.authenticate.addresses.get', 'uses' => 'UserController@getAddresses']);
      Route::post('/authenticate/addresses', ['as' => 'api.v1.authenticate.addresses.post', 'uses' => 'UserController@postAddresses']);
      Route::get('/authenticate/addresses/{id}', ['as' => 'api.v1.authenticate.addresses.single.get', 'uses' => 'UserController@getAddress']);
      Route::patch('/authenticate/addresses/{id}', ['as' => 'api.v1.authenticate.addresses.single.patch', 'uses' => 'UserController@patchAddress']);
      Route::delete('/authenticate/addresses/{id}', ['as' => 'api.v1.authenticate.addresses.single.delete', 'uses' => 'UserController@deleteAddress']);
      // user - cart
      Route::get('/authenticate/cart', ['as' => 'api.v1.authenticate.cart.get', 'uses' => 'UserController@getCart']);
      Route::post('/authenticate/cart', ['as' => 'api.v1.authenticate.cart.post', 'uses' => 'UserController@postCart']);
      Route::delete('/authenticate/cart', ['as' => 'api.v1.authenticate.cart.delete', 'uses' => 'UserController@deleteCart']);
      // user - orders
      Route::post('/authenticate/order', ['as' => 'api.v1.authenticate.order.post', 'uses' => 'OrderController@postOrder']);
      Route::patch('/authenticate/order', ['as' => 'api.v1.authenticate.order.patch', 'uses' => 'OrderController@patchOrder']);
      Route::post('/authenticate/order/coupon', ['as' => 'api.v1.authenticate.order.coupon.patch', 'uses' => 'OrderController@postCoupon']);
      Route::post('/authenticate/verify', ['as' => 'api.v1.authenticate.verify.post', 'uses' => 'UserController@postVerify']);
      Route::post('/authenticate/resend-otp', ['as' => 'api.v1.authenticate.resend_otp.post', 'uses' => 'UserController@postResendOTP']);

      // items
      Route::get('/items', ['as' => 'api.v1.items.index', 'uses' => 'ItemController@index']);
      Route::get('/items/search', ['as' => 'api.v1.items.search', 'uses' => 'ItemController@getSearch']);
      Route::get('/items/{id}', ['as' => 'api.v1.items.show', 'uses' => 'ItemController@show']);

      // orders


    });

});




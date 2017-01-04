<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('address_user', function ($table) {
        $table->index('user_id');
        $table->index('area_id');
      });
      Schema::table('area_kitchen', function ($table) {
        $table->index('kitchen_id');
        $table->index('area_id');
      });
      Schema::table('areas', function ($table) {
        $table->index('city_id');
      });

      Schema::table('availability_item', function ($table) {
        $table->index('item_id');
        $table->index('availability_id');
      });

    Schema::table('cart', function ($table) {
        $table->index('user_id');
        $table->index('item_id');
      });

    Schema::table('check_coupon', function ($table) {
        $table->index('coupon_id');
      });

     Schema::table('cities', function ($table) {
        $table->index('state_id');
      });

     Schema::table('combo_meal', function ($table) {
        $table->index('combo_id');
        $table->index('meal_id');
      });

     Schema::table('combos', function ($table) {
        $table->index('spice_level');
        $table->index('cuisine_id');
        $table->index('category_id');
        $table->index('type');
      });

     Schema::table('coupon_giveaway', function ($table) {
        $table->index('coupon_id');
      });

     Schema::table('favorites', function ($table) {
        $table->index('user_id');
        $table->index('item_id');
      });

     Schema::table('item_order', function ($table) {
        $table->index('order_id');
        $table->index('item_id');
      });

     Schema::table('items', function ($table) {
        $table->index('itemable_id');
      });

     Schema::table('kitchen_managers', function ($table) {
        $table->index('user_id');
        $table->index('kitchen_id');
      });

     Schema::table('log_order', function ($table) {
        $table->index('order_id');
        $table->index('user_id');
        $table->index('status_from_id');
        $table->index('status_to_id');
      });

     Schema::table('meals', function ($table) {
        $table->index('type');
        $table->index('spice_level');
        $table->index('cuisine_id');
        $table->index('category_id');
      });

     Schema::table('order_invoice', function ($table) {
        $table->index('order_id');
      });

     Schema::table('orders', function ($table) {
        $table->index('user_id');
        $table->index('source_id');
        $table->index('payment_mode_id');
        $table->index('coupon_id');
        $table->index('delivery_boy_id');
        $table->index('ngo_id');
        $table->index('kitchen_id');
        $table->index('cancel_reason_id');
        $table->index('delivery_area_id');
        $table->index('delivery_boy_uid');
        $table->index('resource_order_no');
      });

     Schema::table('payments', function ($table) {
        $table->index('order_id');
        $table->index('transaction_id');
        $table->index('payment_mode_id');
      });

     Schema::table('recommended_items', function ($table) {
        $table->index('item_id');
        $table->index('recommended_item_id');
      });

     Schema::table('role_permissions', function ($table) {
        $table->index('role_id');
        $table->index('permission_id');
      });

     Schema::table('roles', function ($table) {
        $table->index('group_id');
      });

     Schema::table('stocks', function ($table) {
        $table->index('item_id');
        $table->index('kitchen_id');
      });

     Schema::table('users', function ($table) {
        $table->index('group_id');
      });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}

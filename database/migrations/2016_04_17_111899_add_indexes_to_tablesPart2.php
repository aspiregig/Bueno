<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToTablesPart2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_texts', function ($table) {
        $table->index('status');
      });
       Schema::table('address_user', function ($table) {
        $table->index('is_default');
        $table->index('disabled');
      });
      Schema::table('areas', function ($table) {
        $table->index('status');
      });
      Schema::table('availabilities', function ($table) {
        $table->index('status');
      });

      Schema::table('categories', function ($table) {
        $table->index('status');
      });

     Schema::table('cities', function ($table) {
        $table->index('status');
      });

     Schema::table('combos', function ($table) {
        $table->index('slug');
        $table->index('is_xprs');
        $table->index('is_sellable');
        $table->index('is_hot_deal');
        $table->index('status');
      });

     Schema::table('meals', function ($table) {
        $table->index('slug');
        $table->index('is_xprs');
        $table->index('is_sellable');
        $table->index('is_hot_deal');
        $table->index('status');
      });

     Schema::table('coupons', function ($table) {
        $table->index('code');
        $table->index('type');
        $table->index('status');
      });

     Schema::table('cuisines', function ($table) {
        $table->index('status');
        $table->index('sequence');
      });

     Schema::table('days', function ($table) {
        $table->index('status');
      });

     Schema::table('delivery_boys', function ($table) {
        $table->index('kitchen_id');
      });

     Schema::table('headers', function ($table) {
        $table->index('status');
      });

     Schema::table('home_sliders', function ($table) {
        $table->index('status');
      });

     Schema::table('items', function ($table) {
        $table->index('itemable_type');
        $table->index('is_recommended');
      });

     Schema::table('jooleh_log', function ($table) {
        $table->index('order_id');
      });

     Schema::table('kitchens', function ($table) {
        $table->index('status');
      });

     Schema::table('meal_spice_level', function ($table) {
        $table->index('status');
      });

     Schema::table('meal_type', function ($table) {
        $table->index('status');
      });

     Schema::table('ngos', function ($table) {
        $table->index('status');
      });

     Schema::table('orders', function ($table) {
        $table->index('is_printed');
        $table->index('status');
      });

     Schema::table('pages', function ($table) {
        $table->index('status');
        $table->index('slug');
      });

     Schema::table('payments', function ($table) {
        $table->index('status');
      });


     Schema::table('payment_modes', function ($table) {
        $table->index('status');
      });


     Schema::table('permissions', function ($table) {
        $table->index('is_allowed');
      });

     Schema::table('roles', function ($table) {
        $table->index('status');
      });

     Schema::table('states', function ($table) {
        $table->index('status');
      });

     Schema::table('testimonials', function ($table) {
        $table->index('user_id');
        $table->index('status');
      });

     Schema::table('timings', function ($table) {
        $table->index('from');
        $table->index('to');
      });

     Schema::table('users', function ($table) {
        $table->index('phone');
        $table->index('email');
        $table->index('membership_id');
        $table->index('default_address_id');
        $table->index('referrer_id');
        $table->index('status');
      });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

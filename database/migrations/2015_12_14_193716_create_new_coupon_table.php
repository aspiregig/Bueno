<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewCouponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::drop('coupons');

      Schema::create('coupons', function (Blueprint $table) {
        $table->increments('id');
        $table->string('code');
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::drop('coupons');

      Schema::create('coupons', function (Blueprint $table) {
        $table->increments('id');
        $table->string('code');
        $table->integer('discount_type');
        $table->float('discount_value');
        $table->float('min_order_amount');
        $table->integer('min_order_quantity');
        $table->float('max_discount_amount');
        $table->float('cashback_value');
        $table->float('loyality_point');
        $table->integer('auto_apply');
        $table->date('valid_from');
        $table->date('valid_to');
        $table->integer('one_time_use');
        $table->integer('status');
        $table->timestamps();
      });
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderNoColumnOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('orders', function ($table) {
      $table->string('order_no');
      $table->string('delivery_address');
      $table->string('delivery_area_id');
      $table->dropColumn('address_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('orders', function ($table) {
      $table->dropColumn('order_no');
      $table->dropColumn('delivery_address');
      $table->dropColumn('delivery_area_id');
      $table->integer('address_id');
    });
  }
}

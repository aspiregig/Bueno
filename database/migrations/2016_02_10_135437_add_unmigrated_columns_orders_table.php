<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnmigratedColumnsOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
  {
    Schema::table('orders', function ($table) {
      $table->string('ip_address')->nullable();
      $table->string('resource_order_no')->nullable();
      $table->string('longitude')->nullable();
      $table->string('latitude')->nullable();
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
      $table->dropColumn('ip_address');
      $table->dropColumn('resource_order_no');
      $table->dropColumn('longitude');
      $table->dropColumn('latitude');
    });
  }
}

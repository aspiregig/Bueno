<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentStatusColumnLogOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
  {
    Schema::table('log_order', function ($table) {
      $table->integer('payment_status_from');
      $table->integer('payment_status_to');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('log_order', function ($table) {
      $table->dropColumn('payment_status_from');
      $table->dropColumn('payment_status_to');
    });
  }
}

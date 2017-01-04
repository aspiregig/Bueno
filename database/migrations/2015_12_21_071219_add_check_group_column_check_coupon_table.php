<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCheckGroupColumnCheckCouponTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('check_coupon', function ($table) {
      $table->integer('group');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('check_coupon', function ($table) {
      $table->dropColumn('group');
    });
  }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusColumnPaymentModesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
  {
    Schema::table('payment_modes', function ($table) {
      $table->integer('status');
      $table->string('description')->nullable();
      $table->string('logo_filename')->nullable();
      $table->string('logo_mime_type')->nullable();
      $table->string('show_order');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('payment_modes', function ($table) {
      $table->dropColumn('status');
      $table->dropColumn('description');
      $table->dropColumn('logo_filename');
      $table->dropColumn('logo_mime_type');
      $table->dropColumn('show_order');
    });
  }
}

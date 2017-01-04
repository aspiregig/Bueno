<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChargeTaxAttributeColumnsKitchensTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('kitchens', function ($table) {
      $table->float('delivery_charge');
      $table->float('packaging_charge');
      $table->float('vat');
      $table->float('service_tax');
      $table->float('service_charge');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('kitchens', function ($table) {
      $table->dropColumn('delivery_charge');
      $table->dropColumn('packaging_charge');
      $table->dropColumn('vat');
      $table->dropColumn('service_tax');
      $table->dropColumn('service_charge');
    });
  }
}

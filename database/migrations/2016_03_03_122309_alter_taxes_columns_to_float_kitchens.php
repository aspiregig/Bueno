<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTaxesColumnsToFloatKitchens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('kitchens', function ($table) {
      $table->float('delivery_charge')->change();
      $table->float('packaging_charge')->change();
      $table->float('vat')->change();
      $table->float('service_tax')->change();
      $table->float('service_charge')->change();
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

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddComputedDistanceAndEtaToOrder extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function ($table) {
            $table->float('computed_travel_distance')->nullable();
            $table->float('computed_rider_delivery_time')->nullable();
            $table->decimal('pickup_latitude', 18, 15)->nullable();
            $table->decimal('pickup_longitude', 18, 15)->nullable();
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
            $table->dropColumn('computed_travel_distance');
            $table->dropColumn('computed_rider_delivery_time');
            $table->dropColumn('pickup_latitude');
            $table->dropColumn('pickup_longitude');
        });
    }
}

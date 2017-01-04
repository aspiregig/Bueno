<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeliveryColumnToOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function ($table) {
            $table->float('travel_distance')->nullable();
            $table->float('total_delivery_time')->nullable();
            $table->float('rider_delivery_time')->nullable();
            $table->decimal('delivery_latitude', 18, 15)->nullable();
            $table->decimal('delivery_longitude', 18, 15)->nullable();
            $table->timestamp('picked_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
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
            $table->dropColumn('travel_distance');
            $table->dropColumn('total_delivery_time');
            $table->dropColumn('rider_delivery_time');
            $table->dropColumn('delivery_latitude');
            $table->dropColumn('delivery_longitude');
            $table->dropColumn('picked_at');
            $table->dropColumn('delivered_at');
        });
    }
}

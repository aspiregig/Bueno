<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConformationEmailCoulumnOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function ($table) {
            $table->boolean('is_sent_confirmation_email');
        });
        Schema::table('users', function ($table) {
            $table->boolean('agree_terms');
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
        $table->dropColumn('is_sent_confirmation_email');
      });

      Schema::table('users', function ($table) {
        $table->dropColumn('agree_terms');
      });
    }
}

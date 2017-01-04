<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('password', 60);
            $table->string('avatar_url');
            $table->string('avatar_mime_type');
            $table->string('default_address_id');
            $table->integer('group_id');
            $table->date('date_of_birth');
            $table->string('referral_code');
            $table->string('referrer_id');
            $table->string('email_otp');
            $table->string('email_verify');
            $table->string('mobile_otp');
            $table->string('mobile_verify');
            $table->rememberToken();
            $table->timestamp('last_login_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}

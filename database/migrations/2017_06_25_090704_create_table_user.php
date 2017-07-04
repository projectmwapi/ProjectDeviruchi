<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->integer('role_id')->nullable();
            $table->string('email');
            // $table->string('username')->nullable();
            $table->string('password');
            $table->string('user_token')->nullable();
            $table->string('user_image')->nullable();
            $table->tinyInteger('first_login')->default(1);
            $table->tinyInteger('invalid_attempt')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamp('last_login_date')->nullable();
            $table->softDeletes();
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
        Schema::drop('user');
    }
}

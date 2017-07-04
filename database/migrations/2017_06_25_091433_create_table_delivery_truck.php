<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDeliveryTruck extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_truck', function (Blueprint $table) {
            $table->increments('delivery_truck_id');
            $table->string('plate_number')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('is_owned')->default(1); // 1 = OWNED 2 = OUTSOURCED
            $table->tinyInteger('is_active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('delivery_truck');
    }
}

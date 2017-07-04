<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMaterialRequestItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_request_item', function (Blueprint $table) {
            $table->increments('request_item_id');
            $table->integer('request_id');
            $table->integer('material_id');
            $table->date('delivery_date');
            $table->integer('classification_id')->nullable();
            $table->integer('request_qty')->nullable();
            $table->integer('purchasing_uom_id')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::drop('material_request_item');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMaterial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material', function (Blueprint $table) {
            $table->increments('material_id');
            $table->string('material_code', 150);
            $table->text('description')->nullable();
            $table->string('material_image')->nullable();
            $table->integer('classification_id')->nullable();
            $table->integer('system_group_id')->nullable();
            $table->integer('purchasing_uom_id')->nullable();
            $table->integer('selling_uom_id')->nullable();
            $table->decimal('conversion_from')->nullable();
            $table->decimal('conversion_to')->nullable();
            $table->decimal('weight')->nullable();
            $table->decimal('area')->nullable();
            $table->integer('packing_unit_id')->nullable(); // BOX, CASE, PALLET
            $table->integer('material_column')->nullable();
            $table->integer('material_row')->nullable();
            $table->integer('minimum_stock_level')->nullable();
            $table->integer('cycle_count_id');
            $table->text('remarks')->nullable();
            $table->tinyInteger('is_active')->default(1);
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
        Schema::drop('material');
    }
}

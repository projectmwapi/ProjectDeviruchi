<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMaterialRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_request', function (Blueprint $table) {
            $table->increments('request_id');
            $table->string('request_number');
            $table->integer('department_id');
            $table->integer('project_id')->nullable();
            $table->string('project_title')->nullable();
            $table->integer('quotation_id')->nullable();
            $table->integer('unique_id')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->text('request_notes')->nullable();
            $table->timestamp('request_date');
            $table->tinyInteger('request_status')->defaut(0); // 0 = PEDNING APPROVAL; 1 = APPROVED; 2 = DECLINED; 3 = CANCELLED
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
        Schema::drop('material_request');
    }
}

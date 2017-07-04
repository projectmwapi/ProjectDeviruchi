<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDepartmentApprover extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_approver', function (Blueprint $table) {
            $table->increments('approver_id');
            $table->integer('department_id');
            $table->integer('user_id');
            $table->tinyInteger('approver_type');
            $table->tinyInteger('is_active')->default(1);
            // $table->softDeletes();
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
        Schema::drop('department_approver');
    }
}

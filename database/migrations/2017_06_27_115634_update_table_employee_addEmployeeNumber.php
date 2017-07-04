<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableEmployeeAddEmployeeNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee', function (Blueprint $table) {
            $table->string('employee_number')->after('user_id')->nullable();
            $table->integer('department_id')->after('employee_number')->nullable();
            $table->text('remarks')->after('last_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee', function (Blueprint $table) {
            $table->dropColumn('employee_number');
            $table->dropColumn('department_id');
            $table->dropColumn('remarks');
        });
    }
}

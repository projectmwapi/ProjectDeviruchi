<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCurrencyConversion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_conversion', function (Blueprint $table) {
            $table->increments('conversion_id');
            $table->integer('original_currency_id')->nullable();
            $table->integer('converted_currency_id')->nullable();
            $table->decimal('conversion_rate')->nullable();
            $table->date('effective_date')->nullable();
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
        Schema::drop('currency_conversion');
    }
}

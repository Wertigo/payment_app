<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableExchangeRate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('currency')->nullable(false);
            $table->bigInteger('rate')->unsigned()->nullable(false);
            $table->date('date')->useCurrent();
            $table->unique(['currency', 'date'], 'unique_currency_rate_for_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exchange_rates', function (Blueprint $table) {
            $table->dropIndex('unique_currency_rate_for_date');
            $table->drop();
        });
    }
}

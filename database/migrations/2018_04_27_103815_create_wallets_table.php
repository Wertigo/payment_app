<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->unsigned()->unique();
            $table->string('currency')->nullable(false);
            $table->bigInteger('money')->unsigned()->nullable(false);
            $table->foreign('user_id', 'user_fk')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallets', function(Blueprint $table) {
            $table->dropForeign('user_fk');
            $table->drop();
        });
    }
}

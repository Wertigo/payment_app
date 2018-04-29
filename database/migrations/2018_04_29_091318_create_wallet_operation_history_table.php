<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletOperationHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_operation_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('wallet_id')->unsigned()->nullable(false);
            $table->integer('type')->nullable(false);
            $table->integer('from_wallet_id')->unsigned()->nullable(true);
            $table->bigInteger('money')->unsigned()->nullable(false);
            $table->bigInteger('from_money')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('wallet_id', 'wallet_owner_fk')->references('id')->on('wallets')->onDelete('cascade');
            $table->foreign('from_wallet_id', 'wallet_from_fk')->references('id')->on('wallets')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallet_operation_histories', function (Blueprint $table) {
            $table->dropForeign('wallet_from_fk');
            $table->dropForeign('wallet_owner_fk');
            $table->drop();
        });
    }
}

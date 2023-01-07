<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_payment_transactions', function (Blueprint $table) {
            $table->foreign(['sale_payment_account_id'], 'sale_payment_transactions_ibfk_2')->references(['id'])->on('sale_payment_accounts');
            $table->foreign(['sale_id'], 'sale_payment_transactions_ibfk_1')->references(['id'])->on('sales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_payment_transactions', function (Blueprint $table) {
            $table->dropForeign('sale_payment_transactions_ibfk_2');
            $table->dropForeign('sale_payment_transactions_ibfk_1');
        });
    }
};

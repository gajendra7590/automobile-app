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
        Schema::table('purchase_dealer_payment_history', function (Blueprint $table) {
            $table->foreign(['bank_account_id'], 'purchase_dealer_payment_history_ibfk_1')->references(['id'])->on('bank_accounts')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_dealer_payment_history', function (Blueprint $table) {
            $table->dropForeign('purchase_dealer_payment_history_ibfk_1');
        });
    }
};

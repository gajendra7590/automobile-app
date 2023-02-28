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
        Schema::table('purchase_transfers', function (Blueprint $table) {
            $table->foreign(['purchase_id'], 'purchase_transfers_ibfk_1')->references(['id'])->on('purchases');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_transfers', function (Blueprint $table) {
            $table->dropForeign('purchase_transfers_ibfk_1');
        });
    }
};

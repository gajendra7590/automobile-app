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
        Schema::table('rto_agent_payment_history', function (Blueprint $table) {
            $table->foreign(['rto_agent_id'], 'rto_agent_payment_history_ibfk_1')->references(['id'])->on('rto_agents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rto_agent_payment_history', function (Blueprint $table) {
            $table->dropForeign('rto_agent_payment_history_ibfk_1');
        });
    }
};

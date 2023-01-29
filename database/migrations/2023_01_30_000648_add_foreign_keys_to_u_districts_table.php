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
        Schema::table('u_districts', function (Blueprint $table) {
            $table->foreign(['state_id'], 'u_districts_ibfk_1')->references(['id'])->on('u_states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('u_districts', function (Blueprint $table) {
            $table->dropForeign('u_districts_ibfk_1');
        });
    }
};

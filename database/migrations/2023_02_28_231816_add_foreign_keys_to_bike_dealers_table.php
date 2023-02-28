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
        Schema::table('bike_dealers', function (Blueprint $table) {
            $table->foreign(['branch_id'], 'bike_dealers_ibfk_1')->references(['id'])->on('branches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bike_dealers', function (Blueprint $table) {
            $table->dropForeign('bike_dealers_ibfk_1');
        });
    }
};

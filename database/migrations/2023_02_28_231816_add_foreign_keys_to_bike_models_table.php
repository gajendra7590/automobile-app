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
        Schema::table('bike_models', function (Blueprint $table) {
            $table->foreign(['brand_id'], 'bike_models_ibfk_1')->references(['id'])->on('bike_brands');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bike_models', function (Blueprint $table) {
            $table->dropForeign('bike_models_ibfk_1');
        });
    }
};

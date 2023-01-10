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
        Schema::table('quotations', function (Blueprint $table) {
            $table->foreign(['branch_id'], 'quotations_ibfk_4')->references(['id'])->on('branches');
            $table->foreign(['bike_brand'], 'quotations_ibfk_1')->references(['id'])->on('bike_brands');
            $table->foreign(['bike_color'], 'quotations_ibfk_3')->references(['id'])->on('bike_colors');
            $table->foreign(['bike_model'], 'quotations_ibfk_2')->references(['id'])->on('bike_models');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropForeign('quotations_ibfk_4');
            $table->dropForeign('quotations_ibfk_1');
            $table->dropForeign('quotations_ibfk_3');
            $table->dropForeign('quotations_ibfk_2');
        });
    }
};

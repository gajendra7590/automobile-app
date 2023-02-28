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
        Schema::table('purchases', function (Blueprint $table) {
            $table->foreign(['gst_rate'], 'purchases_ibfk_4')->references(['id'])->on('gst_rates')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['battery_brand_id'], 'purchases_ibfk_3')->references(['id'])->on('battery_brands');
            $table->foreign(['tyre_brand_id'], 'purchases_ibfk_2')->references(['id'])->on('tyre_brands');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign('purchases_ibfk_4');
            $table->dropForeign('purchases_ibfk_3');
            $table->dropForeign('purchases_ibfk_2');
        });
    }
};

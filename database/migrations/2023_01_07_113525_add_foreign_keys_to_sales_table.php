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
        Schema::table('sales', function (Blueprint $table) {
            $table->foreign(['quotation_id'], 'sales_ibfk_4')->references(['id'])->on('quotations')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['bike_color'], 'sales_ibfk_3')->references(['id'])->on('bike_colors')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign('sales_ibfk_4');
            $table->dropForeign('sales_ibfk_3');
        });
    }
};

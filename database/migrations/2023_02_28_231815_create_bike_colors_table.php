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
        Schema::create('bike_colors', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('bike_model')->nullable();
            $table->integer('model_variant_id')->nullable()->default(0);
            $table->string('color_name', 50)->nullable();
            $table->string('color_code', 50)->nullable();
            $table->string('sku_code', 50)->nullable();
            $table->tinyInteger('active_status')->nullable()->default(1)->comment('0 => In Active, 1 => Active');
            $table->boolean('is_editable')->unsigned()->nullable()->default(true)->comment('0=> is not editable 1 -> is wditable ');
            $table->unsignedInteger('sku_sale_price_id')->nullable()->default(0);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bike_colors');
    }
};

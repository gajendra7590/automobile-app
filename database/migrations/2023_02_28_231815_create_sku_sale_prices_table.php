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
        Schema::create('sku_sale_prices', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('model_color_id')->nullable()->default(0);
            $table->string('sku_code', 50)->nullable()->unique('purchase_id');
            $table->decimal('ex_showroom_price', 10)->nullable();
            $table->decimal('registration_amount', 10)->nullable();
            $table->decimal('insurance_amount', 10)->nullable();
            $table->decimal('hypothecation_amount', 10)->nullable();
            $table->decimal('accessories_amount', 10)->nullable();
            $table->decimal('other_charges', 10)->nullable();
            $table->decimal('total_amount', 10)->nullable();
            $table->boolean('active_status')->nullable()->default(true)->comment('0 => In Active, 1 => Active');
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
        Schema::dropIfExists('sku_sale_prices');
    }
};

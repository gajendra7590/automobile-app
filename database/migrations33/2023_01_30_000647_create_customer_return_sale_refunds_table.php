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
        Schema::create('customer_return_sale_refunds', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('sale_id')->nullable()->default(0);
            $table->integer('sale_account_id')->nullable()->default(0);
            $table->decimal('amount_refund', 10)->nullable()->default(0);
            $table->string('amount_refund_source', 50)->nullable();
            $table->date('amount_refund_date')->nullable();
            $table->text('payment_refund_note')->nullable();
            $table->string('payment_collected_by', 100)->nullable();
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
        Schema::dropIfExists('customer_return_sale_refunds');
    }
};

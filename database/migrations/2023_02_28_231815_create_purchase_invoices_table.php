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
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('sno')->nullable()->default(0);
            $table->integer('year')->nullable()->default(0);
            $table->integer('purchase_id')->unique('purchase_id')->comment('MAP With PURCHASE TABLE');
            $table->string('purchase_invoice_number', 100)->nullable();
            $table->date('purchase_invoice_date')->nullable();
            $table->integer('gst_rate_id')->nullable();
            $table->decimal('gst_rate_percent', 10)->nullable();
            $table->decimal('pre_gst_amount', 10)->nullable();
            $table->decimal('gst_amount', 10)->nullable();
            $table->decimal('ex_showroom_price', 10)->nullable();
            $table->decimal('discount_price', 10)->nullable();
            $table->decimal('grand_total', 10)->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('purchase_invoices');
    }
};

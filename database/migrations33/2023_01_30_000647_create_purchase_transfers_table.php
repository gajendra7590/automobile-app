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
        Schema::create('purchase_transfers', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('purchase_id')->index('purchase_id');
            $table->integer('broker_id')->nullable()->default(0);
            $table->date('transfer_date')->nullable();
            $table->text('transfer_note')->nullable();
            $table->date('return_date')->nullable();
            $table->text('return_note')->nullable();
            $table->decimal('total_price_on_road', 10)->nullable()->default(0);
            $table->tinyInteger('status')->nullable()->default(0)->comment('0 => Transfer, 1 => Returned.');
            $table->tinyInteger('active_status')->nullable()->default(1)->comment('1 => Current Active, 0 => In Active');
            $table->integer('created_by')->nullable()->default(0);
            $table->integer('updated_by')->nullable()->default(0);
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
        Schema::dropIfExists('purchase_transfers');
    }
};

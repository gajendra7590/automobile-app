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
        Schema::create('purchase_dealer_payment_history', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('sno')->nullable()->default(0);
            $table->integer('year')->nullable()->default(0);
            $table->integer('dealer_id')->index('rto_agent_id');
            $table->boolean('transaction_type')->nullable()->default(true)->comment('1 => Debit, 2 => Credit');
            $table->integer('bank_account_id')->nullable()->index('bank_account_id');
            $table->decimal('credit_amount', 10)->nullable()->default(0);
            $table->decimal('debit_amount', 10)->nullable()->default(0);
            $table->string('payment_mode', 50)->nullable();
            $table->text('payment_note')->nullable();
            $table->date('payment_date')->nullable();
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
        Schema::dropIfExists('purchase_dealer_payment_history');
    }
};

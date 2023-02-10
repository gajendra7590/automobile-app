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
        Schema::create('sale_payment_transactions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('sale_id')->index('sale_id');
            $table->integer('sale_payment_account_id')->index('sale_payment_account_id');
            $table->boolean('transaction_for')->nullable()->default(true)->comment('1 => Self Pay, 2 => Bank Finance, 3 => Personal Finance');
            $table->string('transaction_name')->nullable();
            $table->decimal('transaction_amount', 10)->nullable();
            $table->string('transaction_paid_source', 100)->nullable()->default('Cash')->comment('Cash | Cheque | Netbanking | UPI | Credit Card | Debit Card');
            $table->text('transaction_paid_source_note')->nullable();
            $table->date('transaction_paid_date')->nullable();
            $table->boolean('trans_type')->nullable()->default(true)->comment('1 => Credit , 2 => Debit');
            $table->string('status', 10)->nullable()->default('0')->comment('0 => Pending, 1 => Paid, 2 => On Hold ,3 => Failed');
            $table->integer('reference_id')->nullable()->default(0)->index('sale_payment_installment_id');
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
        Schema::dropIfExists('sale_payment_transactions');
    }
};

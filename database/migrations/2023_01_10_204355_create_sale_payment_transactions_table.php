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
            $table->string('transaction_uuid', 100)->comment('trans_');
            $table->integer('sale_id')->index('sale_id');
            $table->integer('sale_payment_account_id')->index('sale_payment_account_id');
            $table->string('transaction_title')->nullable();
            $table->decimal('amount_paid', 10)->nullable();
            $table->string('amount_paid_source', 100)->nullable()->default('Cash')->comment('Cash | Cheque | Netbanking | UPI | Credit Card | Debit Card');
            $table->text('amount_paid_source_note')->nullable();
            $table->date('amount_paid_date')->nullable();
            $table->string('status', 10)->nullable()->default('0')->comment('1 => Done, 0 => Pending');
            $table->integer('payment_collected_by')->nullable();
            $table->integer('sale_payment_installment_id')->nullable()->index('sale_payment_installment_id');
            $table->boolean('is_editable')->unsigned()->nullable()->default(true)->comment('0=> is not editable 1 -> is wditable ');
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

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
        Schema::create('customer_return_sale_payment_accounts', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('account_uuid', 100)->comment('acc_');
            $table->integer('sale_id')->unique('sale_id');
            $table->decimal('sales_total_amount', 10)->nullable()->default(0);
            $table->decimal('down_payment', 10)->nullable()->default(0);
            $table->tinyInteger('due_payment_source')->nullable()->default(1)->comment('1 -> Self Pay | 2 - Finance | 3 - Personal Finance');
            $table->integer('financier_id')->nullable()->comment('Map With Bank Fin Table');
            $table->string('financier_note')->nullable();
            $table->integer('finance_terms')->nullable()->comment('1 => Monthy, 2 => Quaterly 3 => Half Yearly 4 => Yearly)');
            $table->integer('no_of_emis')->nullable();
            $table->decimal('rate_of_interest', 10)->nullable()->default(0);
            $table->decimal('processing_fees', 10)->nullable()->default(0);
            $table->tinyInteger('status')->nullable()->default(0)->comment('0 => Open | 1 => Paid');
            $table->string('status_closed_note', 50)->nullable();
            $table->string('status_closed_by', 50)->nullable();
            $table->decimal('cash_outstaning_balance', 10)->nullable()->default(0);
            $table->decimal('cash_paid_balance', 10)->nullable()->default(0);
            $table->boolean('cash_status')->nullable()->default(false)->comment('0 => Due, 1 => Paid');
            $table->decimal('bank_finance_outstaning_balance', 10)->nullable()->default(0);
            $table->decimal('bank_finance_paid_balance', 10)->nullable()->default(0);
            $table->boolean('bank_finance_status')->nullable()->default(false)->comment('0 => Due, 1 => Paid');
            $table->decimal('bank_finance_amount', 10)->nullable()->default(0)->comment('Bank Finance Amount');
            $table->decimal('personal_finance_outstaning_balance', 10)->nullable()->default(0);
            $table->decimal('personal_finance_paid_balance', 10)->nullable()->default(0);
            $table->boolean('personal_finance_status')->nullable()->default(false)->comment('0 => Due, 1 => Paid');
            $table->decimal('personal_finance_amount', 10)->nullable()->default(0)->comment('Personal Finance Amount');
            $table->boolean('payment_setup')->nullable()->default(false)->comment('0 => No, 1 => Yes');
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
        Schema::dropIfExists('customer_return_sale_payment_accounts');
    }
};

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
        Schema::create('sale_payment_accounts', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('account_uuid', 100)->comment('acc_');
            $table->integer('sale_id')->unique('sale_id');
            $table->decimal('sales_total_amount', 10)->nullable()->default(0);
            $table->decimal('deposite_amount', 10)->nullable()->default(0);
            $table->date('deposite_date')->nullable();
            $table->string('deposite_source', 20)->nullable()->default('Cash')->comment('Cash | Cheque | Netbanking | UPI | Credit Card | Debit Card');
            $table->text('deposite_source_note')->nullable();
            $table->integer('deposite_collected_by')->nullable()->default(0);
            $table->decimal('due_amount', 10)->nullable()->default(0);
            $table->date('due_date')->nullable();
            $table->text('due_note')->nullable();
            $table->tinyInteger('due_payment_source')->nullable()->default(1)->comment('1 -> Self Pay | 2 - Finance | 3 - Personal Finance');
            $table->integer('financier_id')->nullable()->comment('Map With Bank Fin Table');
            $table->string('financier_note')->nullable();
            $table->integer('finance_terms')->nullable()->comment('1 => Monthy, 2 => Quaterly 3 => Half Yearly 4 => Yearly)');
            $table->integer('no_of_emis')->nullable();
            $table->decimal('rate_of_interest', 10)->nullable()->default(0);
            $table->decimal('processing_fees', 10)->nullable()->default(0);
            $table->decimal('total_pay_with_intrest', 10)->nullable()->default(0)->comment('Only Dues');
            $table->tinyInteger('status')->nullable()->default(0)->comment('0 => open | 1 => closed');
            $table->string('status_closed_note', 50)->nullable();
            $table->string('status_closed_by', 50)->nullable();
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
        Schema::dropIfExists('sale_payment_accounts');
    }
};

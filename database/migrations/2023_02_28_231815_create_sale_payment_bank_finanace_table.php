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
        Schema::create('sale_payment_bank_finanace', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('sno')->nullable()->default(0);
            $table->integer('year')->nullable()->default(0);
            $table->integer('sale_id')->index('sale_id');
            $table->integer('sale_payment_account_id')->index('sale_payment_account_id');
            $table->string('payment_name')->nullable();
            $table->decimal('credit_amount', 10)->nullable()->default(0)->comment('Due Balance / Any Charge Applied');
            $table->decimal('debit_amount', 10)->nullable()->default(0)->comment('If Customer Paid');
            $table->decimal('change_balance', 10)->nullable()->default(0)->comment('After Credit / Debit Retotaled');
            $table->date('due_date')->nullable();
            $table->text('paid_source')->nullable();
            $table->date('paid_date')->nullable();
            $table->text('paid_note')->nullable();
            $table->integer('collected_by')->nullable()->default(0)->comment('SalesMan Name');
            $table->boolean('trans_type')->nullable()->comment('1 => Credit , 2 => Debit');
            $table->boolean('status')->nullable()->default(false)->comment('0 => Pending, 1 => Paid, 2 => On Hold ,3 => Failed');
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
        Schema::dropIfExists('sale_payment_bank_finanace');
    }
};

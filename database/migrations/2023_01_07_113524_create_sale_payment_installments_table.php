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
        Schema::create('sale_payment_installments', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('installment_uuid', 100)->comment('inst_');
            $table->integer('sale_id')->index('sale_id');
            $table->integer('sale_payment_account_id')->index('sale_payment_account_id');
            $table->string('emi_title', 100)->nullable();
            $table->decimal('loan_total_amount', 10)->nullable()->default(0);
            $table->decimal('emi_due_amount', 10)->nullable()->default(0);
            $table->decimal('emi_due_principal', 10)->nullable()->default(0);
            $table->decimal('emi_due_intrest', 10)->nullable()->default(0);
            $table->dateTime('emi_due_date')->nullable();
            $table->decimal('emi_other_adjustment', 10)->nullable()->default(0);
            $table->dateTime('emi_other_adjustment_date')->nullable();
            $table->text('emi_other_adjustment_note')->nullable();
            $table->decimal('emi_due_revised_amount', 10)->nullable()->default(0);
            $table->text('emi_due_revised_note')->nullable();
            $table->decimal('amount_paid', 10)->nullable()->default(0);
            $table->dateTime('amount_paid_date')->nullable();
            $table->string('amount_paid_source')->nullable();
            $table->text('amount_paid_note')->nullable();
            $table->decimal('pay_due', 10)->nullable()->default(0);
            $table->tinyInteger('status')->nullable()->default(0)->comment('0 => Open, 1 => Closed');
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
        Schema::dropIfExists('sale_payment_installments');
    }
};

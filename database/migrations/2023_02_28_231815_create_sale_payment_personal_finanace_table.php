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
        Schema::create('sale_payment_personal_finanace', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('sno')->nullable()->default(0);
            $table->integer('year')->nullable()->default(0);
            $table->integer('sale_id')->index('sale_id');
            $table->integer('sale_payment_account_id')->index('sale_payment_account_id');
            $table->string('payment_name')->nullable();
            $table->decimal('emi_total_amount', 10)->nullable()->default(0);
            $table->decimal('emi_principal_amount', 10)->nullable()->default(0);
            $table->decimal('emi_intrest_amount', 10)->nullable()->default(0);
            $table->dateTime('emi_due_date')->nullable();
            $table->decimal('adjust_amount', 10)->nullable()->default(0);
            $table->dateTime('adjust_date')->nullable();
            $table->text('adjust_note')->nullable();
            $table->decimal('emi_due_revised_amount', 10)->nullable()->default(0);
            $table->text('emi_due_revised_note')->nullable();
            $table->decimal('amount_paid', 10)->nullable()->default(0);
            $table->dateTime('amount_paid_date')->nullable();
            $table->string('amount_paid_source')->nullable();
            $table->text('amount_paid_note')->nullable();
            $table->integer('collected_by')->nullable()->default(0)->comment('Salesman ID');
            $table->boolean('status')->nullable()->default(false)->comment('0 => Pending, 1 => Paid, 2 => On Hold ,3 => Failed ');
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
        Schema::dropIfExists('sale_payment_personal_finanace');
    }
};

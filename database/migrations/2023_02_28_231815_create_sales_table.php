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
        Schema::create('sales', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('sno')->nullable()->default(0);
            $table->integer('year')->nullable()->default(0);
            $table->string('sale_uuid', 50)->nullable()->comment('sale_');
            $table->integer('branch_id')->nullable()->default(1);
            $table->integer('purchase_id')->nullable();
            $table->integer('quotation_id')->nullable()->index('quotation_id');
            $table->integer('salesman_id')->nullable();
            $table->unsignedInteger('customer_gender')->nullable()->default(1)->comment('1 => Mr. , 2 => Mrs., 3 => Miss');
            $table->string('customer_name', 50)->nullable();
            $table->unsignedInteger('customer_relationship')->nullable()->default(1)->comment('1 => S/O, 2 => W/O, 3 => D/O');
            $table->string('customer_guardian_name', 50)->nullable();
            $table->string('customer_address_line', 50)->nullable();
            $table->integer('customer_state')->nullable()->comment('Map with state table');
            $table->integer('customer_district')->nullable()->comment('Map with district table');
            $table->integer('customer_city')->nullable()->comment('Map with city table');
            $table->string('customer_zipcode', 10)->nullable();
            $table->string('customer_mobile_number', 15)->nullable();
            $table->string('customer_mobile_number_alt', 15)->nullable();
            $table->string('customer_email_address', 50)->nullable();
            $table->string('witness_person_name', 50)->nullable();
            $table->string('witness_person_phone', 15)->nullable();
            $table->boolean('payment_type')->nullable()->default(true)->comment('1 => By Cash ,2 => Bank Finance, 3 => Personal Finance');
            $table->string('is_exchange_avaliable', 10)->nullable()->default('No')->comment('No | Yes');
            $table->integer('hyp_financer')->nullable();
            $table->text('hyp_financer_description')->nullable();
            $table->decimal('ex_showroom_price', 10)->nullable()->default(0);
            $table->decimal('registration_amount', 10)->nullable()->default(0);
            $table->decimal('insurance_amount', 10)->nullable()->default(0);
            $table->decimal('hypothecation_amount', 10)->nullable()->default(0);
            $table->decimal('accessories_amount', 10)->nullable()->default(0);
            $table->decimal('other_charges', 10)->nullable()->default(0);
            $table->decimal('total_amount', 10)->nullable()->default(0);
            $table->enum('status', ['open', 'close'])->nullable()->default('open');
            $table->integer('sp_account_id')->nullable()->default(0)->comment('0  => No Account Created, 0 > Account Created');
            $table->integer('rto_account_id')->nullable()->default(0)->comment('0  => No RTO Created, 0 > RTO Created');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->date('sale_date')->nullable();
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
        Schema::dropIfExists('sales');
    }
};

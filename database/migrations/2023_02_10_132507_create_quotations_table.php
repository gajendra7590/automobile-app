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
        Schema::create('quotations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('uuid', 50)->nullable();
            $table->integer('branch_id')->nullable()->default(1)->index('branch_id');
            $table->integer('salesman_id')->nullable()->comment('Map with salesman table');
            $table->integer('customer_gender')->nullable()->comment('1 => Mr. , 2 => Mrs., 3 => Miss');
            $table->string('customer_name', 50)->nullable();
            $table->string('customer_relationship', 50)->nullable()->default('1')->comment('1 => S/O, 2 => W/O, 3 => D/O');
            $table->string('customer_guardian_name', 50)->nullable();
            $table->string('customer_address_line', 50)->nullable();
            $table->integer('customer_state')->nullable()->comment('Map with state table');
            $table->integer('customer_district')->nullable()->comment('Map with district table');
            $table->integer('customer_city')->nullable()->comment('Map with city table');
            $table->string('customer_zipcode', 10)->nullable();
            $table->string('customer_mobile_number', 15)->nullable();
            $table->string('customer_mobile_number_alt', 15)->nullable();
            $table->string('customer_email_address', 50)->nullable();
            $table->string('payment_type', 20)->nullable()->comment('Cash | Finance');
            $table->string('is_exchange_avaliable', 10)->nullable()->default('No')->comment('No | Yes');
            $table->integer('hyp_financer')->nullable();
            $table->text('hyp_financer_description')->nullable();
            $table->date('purchase_visit_date')->nullable();
            $table->date('purchase_est_date')->nullable();
            $table->integer('bike_brand')->nullable()->index('bike_brand');
            $table->integer('bike_model')->nullable()->index('bike_model');
            $table->integer('bike_model_variant')->nullable();
            $table->integer('bike_color')->nullable()->index('bike_color');
            $table->decimal('ex_showroom_price', 10)->nullable()->default(0);
            $table->decimal('registration_amount', 10)->nullable()->default(0);
            $table->decimal('insurance_amount', 10)->nullable()->default(0);
            $table->decimal('hypothecation_amount', 10)->nullable()->default(0);
            $table->decimal('accessories_amount', 10)->nullable()->default(0);
            $table->decimal('other_charges', 10)->nullable()->default(0);
            $table->decimal('total_amount', 10)->nullable()->default(0);
            $table->enum('status', ['open', 'close'])->default('open');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->boolean('is_editable')->unsigned()->nullable()->default(true)->comment('0=> is not editable 1 -> is wditable ');
            $table->text('close_note')->nullable();
            $table->unsignedBigInteger('closed_by')->nullable()->index('closed_by');
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
        Schema::dropIfExists('quotations');
    }
};

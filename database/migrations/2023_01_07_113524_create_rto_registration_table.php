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
        Schema::create('rto_registration', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sale_id')->nullable()->index('sale_id');
            $table->integer('rto_agent_id')->nullable()->index('rto_agent_id');
            $table->string('contact_name', 50)->nullable();
            $table->string('contact_mobile_number', 11)->nullable();
            $table->string('contact_address_line')->nullable();
            $table->integer('contact_state_id')->nullable()->index('contact_state_id');
            $table->integer('contact_district_id')->nullable()->index('contact_district_id');
            $table->integer('contact_city_id')->nullable()->index('contact_city_id');
            $table->string('contact_zipcode', 6)->nullable();
            $table->string('sku', 50)->nullable();
            $table->string('financer_name', 100)->nullable();
            $table->string('financer_id', 50)->nullable();
            $table->integer('gst_rto_rate_id')->nullable()->index('gst_rto_rate_id');
            $table->unsignedDecimal('gst_rto_rate_percentage', 10)->nullable();
            $table->unsignedDecimal('ex_showroom_amount', 10)->nullable()->comment('total_invoice_value');
            $table->decimal('tax_amount', 10)->nullable();
            $table->unsignedDecimal('hyp_amount', 10)->nullable()->comment('hypothecation');
            $table->unsignedDecimal('tr_amount', 10)->nullable()->comment('Temporary Vehicle Registration Number');
            $table->unsignedDecimal('fees', 10)->nullable();
            $table->unsignedDecimal('total_amount', 10)->nullable();
            $table->unsignedDecimal('payment_amout', 10)->nullable();
            $table->date('payment_date')->nullable();
            $table->unsignedInteger('outstanding')->nullable()->default(0);
            $table->string('rc_number', 100)->nullable()->comment('The vehicle RC (Registration Certificate)');
            $table->boolean('rc_status')->unsigned()->nullable()->default(false)->comment('0 => No, 1 => Yes');
            $table->date('submit_date')->nullable();
            $table->date('recieved_date')->nullable();
            $table->date('customer_given_date')->nullable();
            $table->string('bike_number', 100)->nullable();
            $table->longText('remark')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index('created_by');
            $table->unsignedBigInteger('updated_by')->nullable()->index('updated_by');
            $table->boolean('active_status')->unsigned()->nullable()->default(true)->comment('0 => In Active, 1 => Active');
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
        Schema::dropIfExists('rto_registration');
    }
};

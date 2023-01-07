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
            $table->string('sale_uuid', 50)->nullable()->comment('sale_');
            $table->integer('branch_id')->nullable()->default(1);
            $table->integer('purchase_id')->nullable();
            $table->integer('quotation_id')->nullable()->index('quotation_id');
            $table->integer('bike_branch')->nullable()->default(0)->comment('Map with branches');
            $table->integer('bike_dealer')->nullable()->default(0)->comment('Map with bike_dealers');
            $table->integer('bike_brand')->nullable()->default(0)->comment('Map with bike_brands');
            $table->integer('bike_model')->nullable()->default(0)->comment('Map with bike_models');
            $table->integer('bike_color')->nullable()->default(0)->index('bike_color')->comment('Map with bike_colors');
            $table->string('bike_type', 100)->nullable()->default('Bike')->comment('Bike | Scooter');
            $table->string('bike_fuel_type', 100)->nullable()->default('Petrol')->comment('Petrol | Electric | CNG |Diesel');
            $table->string('break_type', 100)->nullable()->default('Normal')->comment('Normal | Disk');
            $table->string('wheel_type', 100)->nullable()->comment('Alloy | Spoke');
            $table->string('vin_number', 100)->nullable()->comment('(Chasis Number) - Vichecal Identification number');
            $table->string('vin_physical_status', 100)->nullable()->default('Good ')->comment('Good / Damaged / Not Recieved');
            $table->string('sku', 100)->nullable();
            $table->text('sku_description')->nullable();
            $table->string('hsn_number', 100)->nullable()->default('87112029(');
            $table->string('engine_number', 100)->nullable();
            $table->string('key_number', 100)->nullable();
            $table->string('service_book_number', 100)->nullable();
            $table->string('tyre_brand_name', 100)->nullable();
            $table->string('tyre_front_number', 100)->nullable();
            $table->string('tyre_rear_number', 100)->nullable();
            $table->string('battery_brand', 100)->nullable();
            $table->string('battery_number', 100)->nullable();
            $table->longText('bike_description')->nullable();
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
            $table->string('customer_email_address', 50)->nullable();
            $table->string('payment_type', 20)->nullable()->comment('Cash | Finance');
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
            $table->tinyInteger('active_status')->nullable()->default(1)->comment('0 => In Active, 1 => Active');
            $table->enum('status', ['open', 'close'])->nullable()->default('open');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('sp_account_id')->nullable()->default(0)->comment('0  => No Account Created, 0 > Account Created');
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

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
        Schema::create('purchases', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('sno')->nullable()->default(0);
            $table->integer('year')->nullable()->default(0);
            $table->string('uuid', 50)->nullable();
            $table->integer('bike_branch')->nullable()->default(0)->comment('Map with branches');
            $table->integer('bike_dealer')->nullable()->default(0)->comment('Map with bike_dealers');
            $table->integer('bike_brand')->nullable()->default(0)->comment('Map with bike_brands');
            $table->integer('bike_model')->nullable()->default(0)->comment('Map with bike_models');
            $table->integer('bike_model_variant')->nullable()->default(0);
            $table->integer('bike_model_color')->nullable()->default(0)->comment('Map with bike_colors');
            $table->string('bike_type', 100)->nullable()->default('Motorcycle')->comment('Motorcycle | Scooter');
            $table->string('bike_fuel_type', 100)->nullable()->default('Petrol')->comment('Petrol | Electric | CNG |Diesel');
            $table->string('break_type', 100)->nullable()->default('Normal')->comment('Normal | Disk');
            $table->string('wheel_type', 100)->nullable()->comment('Alloy | Spoke');
            $table->string('dc_number', 100)->nullable()->comment('Delievery Chalan');
            $table->date('dc_date')->nullable()->comment('Delievery Chalan Date');
            $table->string('vin_number', 100)->nullable()->comment('(Chasis Number) - Vichecal Identification number');
            $table->string('vin_physical_status', 100)->nullable()->default('Good ')->comment('Good / Damaged / Not Recieved');
            $table->string('variant', 100)->nullable();
            $table->string('sku', 100)->nullable();
            $table->text('sku_description')->nullable();
            $table->string('hsn_number', 100)->nullable()->default('87112029(');
            $table->string('engine_number', 100)->nullable();
            $table->string('key_number', 100)->nullable();
            $table->string('service_book_number', 100)->nullable();
            $table->bigInteger('tyre_brand_id')->nullable()->index('tyre_brand_id');
            $table->string('tyre_front_number', 100)->nullable();
            $table->string('tyre_rear_number', 100)->nullable();
            $table->bigInteger('battery_brand_id')->nullable()->index('battery_brand_id');
            $table->string('battery_number', 100)->nullable();
            $table->bigInteger('gst_rate')->nullable()->index('gst_rate');
            $table->decimal('gst_rate_percent', 10)->nullable()->default(0);
            $table->decimal('pre_gst_amount', 10)->nullable()->default(0);
            $table->decimal('gst_amount', 10)->nullable()->default(0);
            $table->decimal('ex_showroom_price', 10)->nullable()->default(0);
            $table->decimal('discount_price', 10)->nullable()->default(0);
            $table->decimal('grand_total', 10)->nullable()->default(0);
            $table->longText('bike_description')->nullable();
            $table->tinyInteger('status')->nullable()->default(1)->comment('1 => Purchased, 2 => Sold');
            $table->bigInteger('created_by')->nullable()->default(0)->comment('Loggedin User Id');
            $table->bigInteger('updated_by')->nullable()->default(0)->comment('Loggedin User Id');
            $table->boolean('is_editable')->unsigned()->nullable()->default(true)->comment('0=> is not editable 1 -> is wditable ');
            $table->boolean('transfer_status')->unsigned()->nullable()->default(false)->comment('0 => Own Showroom, 1 => Broker Showroom');
            $table->boolean('invoice_status')->unsigned()->nullable()->default(false)->comment('0 => Invoice Not Created, 1 => Invoice Created');
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
        Schema::dropIfExists('purchases');
    }
};

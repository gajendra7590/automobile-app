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
        Schema::create('branches', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('branch_name', 100)->nullable();
            $table->string('branch_email', 100)->nullable();
            $table->string('branch_phone', 20)->nullable();
            $table->string('branch_phone2', 20)->nullable();
            $table->string('branch_address_line')->nullable();
            $table->string('branch_city', 100)->nullable();
            $table->string('branch_district', 100)->nullable();
            $table->string('branch_state', 100)->nullable();
            $table->string('branch_county', 100)->nullable()->default('India');
            $table->string('branch_pincode', 20)->nullable();
            $table->text('branch_more_detail')->nullable();
            $table->string('account_number', 50)->nullable();
            $table->string('ifsc_code', 10)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_branch', 100)->nullable();
            $table->text('gstin_number')->nullable();
            $table->tinyInteger('active_status')->nullable()->default(1)->comment('0 => In Active, 1 => Active');
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
        Schema::dropIfExists('branches');
    }
};

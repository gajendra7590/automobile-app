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
        Schema::create('brokers', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 50)->nullable();
            $table->string('email')->nullable();
            $table->string('mobile_number', 20)->nullable();
            $table->string('mobile_number2', 20)->nullable();
            $table->string('aadhar_card', 20)->nullable();
            $table->string('pan_card', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('highest_qualification', 100)->nullable();
            $table->tinyInteger('gender')->nullable()->default(1)->comment('1 => Male, 2 => Female, 3 => Other');
            $table->string('address_line')->nullable();
            $table->string('city', 50)->nullable();
            $table->string('district', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('zipcode', 20)->nullable();
            $table->string('joined_at', 50)->nullable();
            $table->text('more_details')->nullable();
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
        Schema::dropIfExists('brokers');
    }
};

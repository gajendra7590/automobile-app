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
        Schema::create('bank_financers', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('bank_name', 50)->nullable();
            $table->string('bank_branch_code', 50)->nullable();
            $table->string('bank_contact_number', 20)->nullable();
            $table->string('bank_email_address', 50)->nullable();
            $table->text('bank_full_address')->nullable();
            $table->string('bank_manager_name', 50)->nullable();
            $table->string('bank_manager_contact', 20)->nullable();
            $table->string('bank_manager_email', 50)->nullable();
            $table->string('bank_financer_name', 50)->nullable();
            $table->string('bank_financer_contact', 20)->nullable();
            $table->string('bank_financer_email', 50)->nullable();
            $table->text('bank_financer_address')->nullable();
            $table->string('bank_financer_aadhar_card', 20)->nullable();
            $table->string('bank_financer_pan_card', 20)->nullable();
            $table->tinyInteger('active_status')->nullable()->default(1)->comment('0 => In Active, 1 => Active');
            $table->tinyInteger('financer_type')->nullable()->default(1)->comment('1 => Bank Fiancer, 2 => Personal');
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
        Schema::dropIfExists('bank_financers');
    }
};

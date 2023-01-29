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
        Schema::create('bike_dealers', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('dealer_code', 100)->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_office_phone')->nullable();
            $table->text('company_address')->nullable();
            $table->text('company_gst_no')->nullable();
            $table->text('company_more_detail')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_person_email')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->string('contact_person_phone2')->nullable();
            $table->string('contact_person_address')->nullable();
            $table->string('contact_person_document_type')->nullable();
            $table->string('contact_person_document_file')->nullable();
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
        Schema::dropIfExists('bike_dealers');
    }
};

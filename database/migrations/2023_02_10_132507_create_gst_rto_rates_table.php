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
        Schema::create('gst_rto_rates', function (Blueprint $table) {
            $table->integer('id', true);
            $table->decimal('gst_rate', 10)->nullable()->default(0);
            $table->decimal('cgst_rate', 10)->nullable()->default(0);
            $table->decimal('sgst_rate', 10)->nullable()->default(0);
            $table->decimal('igst_rate', 10)->nullable()->default(0);
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
        Schema::dropIfExists('gst_rto_rates');
    }
};

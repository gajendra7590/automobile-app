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
        Schema::create('gst_rates', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->double('gst_rate', 10, 2)->nullable()->default(0);
            $table->double('cgst_rate', 10, 2)->nullable()->default(0);
            $table->decimal('sgst_rate', 10)->nullable()->default(0);
            $table->decimal('igst_rate', 10)->nullable()->default(0);
            $table->tinyInteger('active_status')->nullable()->default(1)->comment('0 => In Active, 1 => Active');
            $table->boolean('is_editable')->unsigned()->nullable()->default(true)->comment('0=> is not editable 1 -> is wditable ');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gst_rates');
    }
};

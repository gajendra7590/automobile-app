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
        Schema::create('bike_model_variants', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('model_id')->nullable()->default(0);
            $table->string('variant_name')->nullable()->index('variant_name');
            $table->tinyInteger('active_status')->nullable()->default(1)->comment('0 => In Active, 1 => Active');
            $table->integer('is_editable')->nullable()->default(1)->index('is_editable')->comment('0=> is not editable 1 -> is editable ');
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
        Schema::dropIfExists('bike_model_variants');
    }
};

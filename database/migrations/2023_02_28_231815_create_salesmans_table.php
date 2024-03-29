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
        Schema::create('salesmans', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('mobile_number', 100)->nullable();
            $table->tinyInteger('active_status')->nullable()->default(1)->comment('0 => In Active, 1 => Active');
            $table->tinyInteger('is_editable')->nullable()->default(1)->comment('0 => is not editable 1 -> is editable ');
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
        Schema::dropIfExists('salesmans');
    }
};

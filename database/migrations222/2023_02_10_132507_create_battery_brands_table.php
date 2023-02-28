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
        Schema::create('battery_brands', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('disable_edit')->unsigned()->nullable()->default(false);
            $table->boolean('active_status')->unsigned()->nullable()->default(true);
            $table->boolean('is_editable')->unsigned()->nullable()->default(true)->comment('0 => is not editable 1 => is editable');
            $table->timestamp('created_at')->useCurrent();
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
        Schema::dropIfExists('battery_brands');
    }
};

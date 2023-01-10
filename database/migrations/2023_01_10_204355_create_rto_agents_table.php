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
        Schema::create('rto_agents', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('agent_name', 50)->nullable();
            $table->string('agent_phone', 11)->nullable();
            $table->string('agent_email', 50)->nullable();
            $table->tinyInteger('active_status')->nullable()->default(1)->comment('1 => Active, 0 => In Active');
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
        Schema::dropIfExists('rto_agents');
    }
};

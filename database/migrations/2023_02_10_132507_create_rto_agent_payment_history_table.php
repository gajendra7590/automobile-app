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
        Schema::create('rto_agent_payment_history', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('rto_agent_id')->index('rto_agent_id');
            $table->decimal('payment_amount', 10)->nullable()->default(0);
            $table->string('payment_mode', 20)->nullable();
            $table->text('payment_note')->nullable();
            $table->date('payment_date')->nullable();
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
        Schema::dropIfExists('rto_agent_payment_history');
    }
};

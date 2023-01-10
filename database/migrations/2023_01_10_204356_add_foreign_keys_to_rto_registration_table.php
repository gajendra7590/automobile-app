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
        Schema::table('rto_registration', function (Blueprint $table) {
            $table->foreign(['updated_by'], 'rto_registration_ibfk_4')->references(['id'])->on('users')->onDelete('NO ACTION');
            $table->foreign(['contact_district_id'], 'rto_registration_ibfk_6')->references(['id'])->on('u_districts')->onDelete('NO ACTION');
            $table->foreign(['gst_rto_rate_id'], 'rto_registration_ibfk_8')->references(['id'])->on('gst_rto_rates');
            $table->foreign(['sale_id'], 'rto_registration_ibfk_1')->references(['id'])->on('sales')->onDelete('NO ACTION');
            $table->foreign(['created_by'], 'rto_registration_ibfk_3')->references(['id'])->on('users')->onDelete('NO ACTION');
            $table->foreign(['contact_state_id'], 'rto_registration_ibfk_5')->references(['id'])->on('u_states')->onDelete('NO ACTION');
            $table->foreign(['contact_city_id'], 'rto_registration_ibfk_7')->references(['id'])->on('u_cities')->onDelete('NO ACTION');
            $table->foreign(['rto_agent_id'], 'rto_registration_ibfk_2')->references(['id'])->on('rto_agents')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rto_registration', function (Blueprint $table) {
            $table->dropForeign('rto_registration_ibfk_4');
            $table->dropForeign('rto_registration_ibfk_6');
            $table->dropForeign('rto_registration_ibfk_8');
            $table->dropForeign('rto_registration_ibfk_1');
            $table->dropForeign('rto_registration_ibfk_3');
            $table->dropForeign('rto_registration_ibfk_5');
            $table->dropForeign('rto_registration_ibfk_7');
            $table->dropForeign('rto_registration_ibfk_2');
        });
    }
};

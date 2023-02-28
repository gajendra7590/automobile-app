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
        Schema::create('document_uploads', function (Blueprint $table) {
            $table->integer('id', true);
            $table->tinyInteger('section_type')->nullable()->default(1);
            $table->integer('section_id')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_extention', 20)->nullable();
            $table->string('file_mime_type')->nullable();
            $table->string('file_size', 10)->nullable();
            $table->text('file_description')->nullable();
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
        Schema::dropIfExists('document_uploads');
    }
};

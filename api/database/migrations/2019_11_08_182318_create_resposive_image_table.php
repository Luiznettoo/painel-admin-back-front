<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResposiveImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resposive_image', function (Blueprint $table) {
            $table->integer('file_id', false, true)->nullable()->index();
            $table->char('uuid', 36)->unique();
            $table->string('mime', 32);
            $table->integer('width');

            $table->foreign('file_id')->references('id')->on('files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resposive_image');
    }
}

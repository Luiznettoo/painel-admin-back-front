<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropFilePreviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::dropIfExists('file_previews');
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::create('file_previews', static function (Blueprint $table) {
            $table->increments('id');
            $table->integer('file_id', false, true)->index();
            $table->string('preview', 255);
            
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
        });
    }
}

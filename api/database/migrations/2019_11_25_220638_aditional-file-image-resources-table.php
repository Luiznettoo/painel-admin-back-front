<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AditionalFileImageResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('file_image_resources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('file_id', false, true);
            $table->char('uuid', 36)->unique();
            $table->string('extension', 6)->index();
            $table->smallInteger('width', false, true)->index();
            
            $table->foreign('file_id')->references('id')->on('files');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('file_image_resources');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ordem');
            $table->string('name');
            $table->string('ref')->unique();
            $table->string('title');
            $table->text('description');
            $table->string('url');
            $table->integer('desta',false,true)->default('0');
            $table->integer('destc',false,true)->default('0');
            //Imagem Principal
            $table->integer('file_id', false, true)->nullable()->default(NULL);
            //Thumb do video
            $table->integer('thumb_id', false, true)->nullable()->default(NULL);
            //Video
            $table->integer('video_id', false, true)->nullable()->default(NULL);
            $table->timestamps();

            $table->foreign('file_id')->references('id')->on('files');
            $table->foreign('thumb_id')->references('id')->on('files');
            $table->foreign('video_id')->references('id')->on('files');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product');
    }
}

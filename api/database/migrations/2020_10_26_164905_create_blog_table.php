<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id');
            $table->string('title');
            $table->text('text');
            $table->text('body');
            $table->integer('file_id', false, true)->nullable()->default(NULL);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();
            $table->integer('capa_id', false, true);
            $table->integer('banner_id', false, true);
            $table->string('autor', 255);
            $table->string('url');
            $table->string('meta_title');
            $table->text('meta_description');
            $table->text('meta_keywords');


            $table->foreign('file_id')->references('id')->on('files');
            $table->foreign('banner_id')->references('id')->on('files');
            $table->foreign('capa_id')->references('id')->on('files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog');
    }
}

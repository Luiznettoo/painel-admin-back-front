<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcabamentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acabament', static function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('text');
            $table->integer('category_id', false, true)->nullable()->default(NULL);
            $table->integer('file_id', false, true)->nullable()->default(NULL);

            $table->foreign('category_id')->references('id')->on('category_acabament');
            $table->foreign('file_id')->references('id')->on('files');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acabament');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryAmbienceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_ambience', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ordem');
            $table->string('name', 64);
            $table->integer('file_id', false, true)->nullable()->default(NULL);
            $table->string('url');

            $table->foreign('file_id')->references('id')->on('files');

            $table->timestamps();
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
        Schema::dropIfExists('category_ambience');
    }
}

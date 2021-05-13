<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableTextBannerAddFileId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('text_banner', static function (Blueprint $table) {
            $table->integer('file_id', false, true)->nullable()->default(NULL);

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
        Schema::table('text_banner', static function (Blueprint $table) {
            $table->integer('file_id');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableBlogAddIdFileAutor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('blog', static function (Blueprint $table) {
            $table->integer('file_id_autor', false, true)->nullable()->default(NULL)->index();

            $table->foreign('file_id_autor')->references('id')->on('files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog', static function (Blueprint $table) {
            $table->dropColumn('file_id_autor');
        });
    }
}

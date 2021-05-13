<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableBlogAddMetaTitleMetaDescriptionMetaKeywords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog', static function (Blueprint $table) {
            $table->string('meta_title');
            $table->text('meta_description');
            $table->text('meta_keywords');
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
            $table->string('meta_title');
            $table->text('meta_description');
            $table->text('meta_keywords');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableServicesAddFriendlyUrlMetaTitleMetaDescriptionMetaKeywords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', static function (Blueprint $table) {
            $table->string('friendly_url', 128)->nullable()->default(NULL)->unique()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services', static function (Blueprint $table) {
            $table->dropColumn('friendly_url');
        });
    }
}

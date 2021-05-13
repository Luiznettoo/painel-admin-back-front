<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableOptionAddOrdem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('option', static function (Blueprint $table) {
            $table->integer('ordem')->default(0)->index();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('option', static function (Blueprint $table) {
            $table->dropColumn('ordem');
        });
    }
}

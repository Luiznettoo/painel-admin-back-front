<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAcabamentAddOrdem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('acabament', static function (Blueprint $table) {
            $table->integer('ordem')->default(0)->index();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('acabament', static function (Blueprint $table) {
            $table->dropColumn('ordem');
        });
    }
}

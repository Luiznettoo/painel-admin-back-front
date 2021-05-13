<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableProductAddPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('product', static function (Blueprint $table) {
            $table->double('price', 10,2)->nullable()->default(NULL)->index();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('product', static function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
}

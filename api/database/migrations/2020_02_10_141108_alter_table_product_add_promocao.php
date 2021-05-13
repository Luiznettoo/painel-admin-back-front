<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableProductAddPromocao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('product', static function (Blueprint $table) {
            $table->double('promotion', 10,2)->nullable()->default(NULL)->index();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('product', static function (Blueprint $table) {
            $table->dropColumn('promotion');
        });
    }
}

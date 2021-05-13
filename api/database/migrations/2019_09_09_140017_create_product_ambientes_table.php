<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAmbientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_ambientes', function (Blueprint $table) {
            $table->integer('product_id',false,true);
            $table->integer('ambiente_id',false,true);

            $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');
            $table->foreign('ambiente_id')->references('id')->on('category_ambience')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_ambientes');
    }
}

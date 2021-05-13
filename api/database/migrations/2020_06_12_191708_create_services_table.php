<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 64);
            $table->integer('file_id', false, true)->nullable()->default(NULL);
            $table->text('descricao'); 
            $table->text('texto');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();
            $table->string('url');
            $table->integer('ordem');
            $table->string('promocao', 64);
            $table->string('horario', 64);
            $table->integer('banner_id', false, true);
            $table->string('atendimento', 64);
            $table->integer('ativo');
            
            $table->foreign('file_id')->references('id')->on('files');
            $table->foreign('banner_id')->references('id')->on('files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}

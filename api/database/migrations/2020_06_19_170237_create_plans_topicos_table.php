<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTopicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('plans_topicos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plan_id', false, true)->nullable(false);
            $table->text('topico');
            $table->timestamps();
            $table->softDeletes();

            $table
                ->foreign('plan_id')
                ->references('id')
                ->on('plans')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans_topicos');
    }
}

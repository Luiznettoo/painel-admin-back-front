<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('file_id', false, true)->nullable()->index();
            $table->char('uuid', 36)->unique();
            $table->string('name', 255)->nullable();
            $table->string('mime', 32)->nullable();
            $table->bigInteger('size', false, true)->nullable();
            $table->string('title', 128)->nullable()->default(NULL);
            $table->string('alt', 128)->nullable()->default(NULL);
            $table->tinyInteger('folder', false, true)->default(0)->index();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();

            $table->foreign('file_id')->references('id')->on('files');
            $table->unique([
                'file_id',
                'name',
                'folder',
                'deleted_at'
            ]);
        });

        DB::table('files')->insert([
            [
                'uuid' => '7e0cc5df-ef6d-4d64-b657-b21cb155e391',
                'name' => 'root',
                'mime' => 'folder',
                'folder' => 1,
            ],
        ]);

        Schema::create('file_previews', static function (Blueprint $table) {
            $table->increments('id');
            $table->integer('file_id', false, true)->index();
            $table->string('preview', 255);

            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_previews');
        Schema::dropIfExists('files');
    }
}

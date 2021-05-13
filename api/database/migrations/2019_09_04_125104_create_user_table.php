<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user', 32)->index();
            $table->string('name', 64);
            $table->string('email', 128)->nullable();
            $table->string('password');
            $table->char('api_token', 64)->nullable()->index();
            $table->timestamp('expires')->nullable()->index();

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();

            $table->unique(['user', 'deleted_at']);
            $table->unique(['email', 'deleted_at']);
            $table->unique(['api_token', 'deleted_at']);
        });

        DB::table('users')->insert([
            ['name' => 'Root','user' => 'root','email' => 'programacao@azulshop.com','password' => Hash::make('azul@digital')],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

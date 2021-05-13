<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $u = new User();
        $u->name = 'root';
        $u->user = 'root';
        $u->email = 'angelo@azulsystems.com.br';
        $u->password = Hash::make('123456');
        $u->save();
    }
}




<?php

use Illuminate\Database\Seeder;
use App\User;

class UserAdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        return User::create([
        	'name' => 'Adminstrador',
        	'email' =>'admin@admin',
        	'password' => Hash::make('administrador_1'),
        	'roleid' => 1,
        	'lastname' => 'gioss',

        ]);
    }
}

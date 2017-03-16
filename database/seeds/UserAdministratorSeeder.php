<?php

use Illuminate\Database\Seeder;
use App\User;

use Illuminate\Support\Facades\Hash;

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
        	'password' => Hash::make('j1807199307'),
        	'roleid' => 1,
        	'lastname' => 'gioss',

        ]);
    }
}

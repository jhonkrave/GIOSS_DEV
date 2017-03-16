<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Model::unguard();
        $this->call(UserAdministratorSeeder::class);
        $this->call(DBtipoEntidadSeeder::class);
        $this->call(DbRolesSeeder::class);
	    //$this->call(DBconsulta_homologoSeeder::class);

	    Model::reguard();
    }
}

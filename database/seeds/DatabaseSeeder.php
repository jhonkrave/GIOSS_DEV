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
        
        $this->call(DbRolesSeeder::class);
        $this->call(UserAdministratorSeeder::class);

        $this->call(sexoSeeder::class);
        $this->call(temaImformacion_Seeder::class);
        $this->call(tipoEAPBSeeder::class);
        $this->call(tipoEntidadSeeder::class);
        $this->call(tipoIdentEAPBSeeder::class);
        $this->call(tipoIdentEntidadSeeder::class);
        $this->call(tipoIdentificacionSeeder::class);
        $this->call(ambitoSeeder::class);
        //$this->call(DBtipoEntidadSeeder::class);
	    //$this->call(DBconsulta_homologoSeeder::class);

	    Model::reguard();
    }
}

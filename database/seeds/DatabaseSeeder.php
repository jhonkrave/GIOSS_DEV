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
        
        $this->call(departamentosSeeder::class);
        $this->call(municipiosSeeder::class);
        $this->call(DbRolesSeeder::class);
        $this->call(UserAdministratorSeeder::class);
        $this->call(sexoSeeder::class);
        $this->call(temaImformacion_Seeder::class);
        $this->call(tipoEAPBSeeder::class);
        $this->call(tipoEntidadSeeder::class);
        $this->call(tipoEntidad2Seeder::class);
        $this->call(tipoIdentEAPBSeeder::class);
        $this->call(tipoIdentEntidadSeeder::class);
        $this->call(tipoIdentificacionSeeder::class);
        $this->call(ambitoSeeder::class);
        $this->call(eapsSeeder::class);
        $this->call(entidadPrestadorSaludSeeder::class);
        $this->call(medicamentoscumSeeder::class);
        $this->call(medicamentosAtcSeeder::class);        
        $this->call(vacunasCupsSeeder::class);
        $this->call(consultasCupsSeeder::class);
        //$this->call(DBtipoEntidadSeeder::class);medicamentosAtcSeeder
	    //$this->call(DBconsulta_homologoSeeder::class);

	    Model::reguard();
    }
}

<?php

use Illuminate\Database\Seeder;

class entidadPrestadorSaludSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //se borra el contenido de la tabla
        DB::table('entidades_sector_salud')->delete();

    	$header = null;
       	$data = array();
       	$filename = storage_path('seeders').'/entidad_prestador_salud.csv';
        DB::unprepared("COPY entidades_sector_salud(tipo_entidad,tipo_identificacion,num_identificacion,cod_habilitacion,nombre,cod_mpio) FROM '".$filename."' DELIMITER ',' CSV HEADER");

        return true;
    }
}

<?php

use Illuminate\Database\Seeder;

class diagnosticoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //se borra el contenido de la tabla
        DB::table('diagnostico_ciex')->delete();

    	$header = null;
       	$data = array();
       	$filename = storage_path('seeders').'/gioss_codigo_diagnostico.csv';
        DB::unprepared("COPY diagnostico_ciex(cod_diagnostico,descripcion,cod_grupo_dx,cod_capitulo_dx,cod_sub_grupo_dx,cod_sexo,edad_min,edad_max) FROM '".$filename."' DELIMITER ',' CSV HEADER");

        return true;
    }
}

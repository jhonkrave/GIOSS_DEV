<?php

use Illuminate\Database\Seeder;

class tipoEntidad2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         //se borra el contenido de la tabla
        DB::table('tipo_entidad')->delete();

    	$header = ['id_tipo_ent','descripcion'];
       	$data = array();
       	$filename = storage_path('seeders').'/tipo_entidad_backup.csv';
        //factory(tipo_entidad::class,4)->create();
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while ($row = fgetcsv($handle, 1000, ','))
            {
                if(!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        //se adiciona el contenido de $data
        DB::table('tipo_entidad')->insert($data);

        return true;
    }
}

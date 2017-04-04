<?php

use Illuminate\Database\Seeder;

class departamentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //se borra el contenido de la tabla
        DB::table('departamentos')->delete();

    	$header = ['cod_divipola','nombre'];
       	$data = array();
       	$filename = storage_path('seeders').'/departamentos.csv';
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
        DB::table('departamentos')->insert($data);

        return true;

    }
}

<?php

use Illuminate\Database\Seeder;

class municipiosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         //se borra el contenido de la tabla
        DB::table('municipios')->delete();

    	$header = ['cod_divipola','cod_depto','nombre'];
       	$data = array();
       	$filename = storage_path('seeders').'/municipios.csv';
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
        DB::table('municipios')->insert($data);

        return true;
    }
}

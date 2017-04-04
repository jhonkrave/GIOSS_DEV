<?php

use Illuminate\Database\Seeder;

class eapsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //se borra el contenido de la tabla
        DB::table('eapbs')->delete();

    	$header = NULL;
       	$data = array();
       	$filename = storage_path('seeders').'/eapbs.csv';
        //factory(tipo_entidad::class,4)->create(); (([0-9]+[0-9]*|[S|P|O|N|C])+[\,])([\s]*)([\,]([0-9]|[a-zA-Z])*[\,][0-9]+[\,])
        if ($handle = fopen($filename,'r'))
        {
            while ($row = fgetcsv($handle,0 ,','))
            {
                if(!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        //se adiciona el contenido de $data
        DB::table('eapbs')->insert($data);

        return true;
    }
}

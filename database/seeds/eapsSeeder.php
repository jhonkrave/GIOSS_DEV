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

        if ($handle = fopen($filename,'r'))
        {
            while ($row = fgetcsv($handle,0 ,','))
            {
                if(!$header)
                {
                    $header = $row;
                }
                else
                {
                   if($row[1] == '') $row[1] = 'SD';
                    $data[] = array_combine($header, $row); 
                }
                    
            }
            fclose($handle);
        }

        //se adiciona el contenido de $data
        DB::table('eapbs')->insert($data);

        return true;
    }
}

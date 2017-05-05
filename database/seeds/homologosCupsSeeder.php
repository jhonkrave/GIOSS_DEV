<?php

use Illuminate\Database\Seeder;

class homologosCupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('homologos_cups_codigos')->delete();

    	$header = null;
       	$data = array();
       	$filename = storage_path('seeders').'/homologosCups.txt';
        if ($handle = fopen($filename,'r'))
        {   
            $count = 1;
            while ($row = fgetcsv($handle,0 ,'|'))
            {
                if(!$header)
                {
                    $header = $row;
                }
                else
                {
                    try {
                        DB::unprepared('INSERT INTO homologos_cups_codigos(cod_homologo,cod_cups) VALUES(\''.trim($row[1]).'\',\''.trim($row[2]).'\')');
                        $count++;
                    } catch (Exception $e) {
                        Log::info("medicamentos seeder error linea  " .$count.'. '.$e->getMessage()." arreglo: ".print_r($row,true));
                        $count++;
                        continue;
                    }
                   
                }
                    
            }
            fclose($handle);
        }

        return true;
    }
}

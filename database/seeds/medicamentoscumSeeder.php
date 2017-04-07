<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class medicamentoscumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('medicamentos_cum')->delete();

    	$header = null;
       	$data = array();
       	$filename = storage_path('seeders').'/Gioss_codigo_medicamentos_cum1.csv';
        if ($handle = fopen($filename,'r'))
        {   
            $count = 1;
            while ($row = fgetcsv($handle,0 ,','))
            {
                if(!$header)
                {
                    $header = $row;
                }
                else
                {
                    try {
                        DB::unprepared('INSERT INTO medicamentos_cum(codigo_medicamento,registro,producto,desrip_comercial_cum,descrip_atc,principio_activo) VALUES(\''.$row[0].'\',\''.$row[1].'\',\''.$row[2].'\',\''.$row[3].'\',\''.$row[4].'\',\''.$row[5].'\')');
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

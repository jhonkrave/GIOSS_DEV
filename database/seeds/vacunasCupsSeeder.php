<?php

use Illuminate\Database\Seeder;

class vacunasCupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vacuna_cups')->delete();

    	$header = null;
       	$data = array();
       	$filename = storage_path('seeders').'/gioss_codigos_vacunacion_cups.csv';
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
                        DB::unprepared('INSERT INTO vacuna_cups(codigo_tipo_vacuna,descripcion,cod_sis_cups,descrip_sis_cups,cod_grup_cups,desc_grup_cups,ambito_cups,sexo_cups,nivel_atencion) VALUES(\''.$row[0].'\',\''.$row[1].'\',\''.$row[2].'\',\''.$row[3].'\',\''.$row[4].'\',\''.$row[5].'\',\''.$row[6].'\',\''.$row[7].'\',\''.$row[8].'\')');
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

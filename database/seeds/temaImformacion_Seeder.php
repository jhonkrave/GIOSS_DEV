<?php

use Illuminate\Database\Seeder;
use App\Models\TemaInformacion;

class temaImformacion_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TemaInformacion::create([
        	'id_tema_informacion' => 'AAC',
        	'descripcion' =>'Archivo atención en consulta.',
  
        ]);

        TemaInformacion::create([
        	'id_tema_informacion' => 'AMS',
        	'descripcion' =>'Archivo medicamentos suministrados.',
  
        ]);

        TemaInformacion::create([
        	'id_tema_informacion' => 'AEH',
        	'descripcion' =>'Archivo egresos hospitalarios.',
  
        ]);

        TemaInformacion::create([
        	'id_tema_informacion' => 'AVA',
        	'descripcion' =>'Archivo vacunas aplicadas.',
  
        ]);

        TemaInformacion::create([
        	'id_tema_informacion' => 'APS',
        	'descripcion' =>'Archivo procedemientos',
  
        ]);

        return true;
    }
}

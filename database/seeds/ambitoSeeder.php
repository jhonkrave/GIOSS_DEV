<?php

use Illuminate\Database\Seeder;
use App\Models\Ambito;

class ambitoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ambito::create([
        	'cod_ambito' => 'A',
        	'descripcion' => 'Ambulatoria (Consulta realizada en Consulta externa)'
        ]);

        Ambito::create([
        	'cod_ambito' => 'H',
        	'descripcion' => ' Hospitalario (Consulta realizada en Internación)'
        ]);

        Ambito::create([
        	'cod_ambito' => 'U',
        	'descripcion' => 'Urgencias (Consulta Realizada en el servicio de Urgencias)'
        ]);

        Ambito::create([
        	'cod_ambito' => 'D',
        	'descripcion' => 'Domiciliario (Consulta Realizada en el extramuros de la entidad)'
        ]);

        return true;
    }
}

<?php

use Illuminate\Database\Seeder;
use App\consulta_homologo;

class DBconsulta_homologoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$seed = new consulta_homologo();
    	$seed->cod_consulta =  'codigo0';
    	$seed->descripcion = 'descripcion0';
       	$seed->save();
    }
}

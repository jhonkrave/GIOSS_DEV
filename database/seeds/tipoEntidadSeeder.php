<?php

use Illuminate\Database\Seeder;
use App\Models\TipoEntidad;

class tipoEntidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        return TipoEntidad::create([
        	'id_tipo_ent' => '239',
        	'descripcion' =>'Entidad Fuente de la Información: Institución Prestadora de Servicios de Salud IPS.',
  
        ]);
    }
}

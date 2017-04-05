<?php

use Illuminate\Database\Seeder;
use App\Models\TipoIdentificacionEntidad;

class tipoIdentEntidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoIdentificacionEntidad::create([
    		'id_tipo_ident' =>'NIT',
    		'descripcion' => 'IDENTIFICACION TRIBUTARIA'
        ]);

        TipoIdentificacionEntidad::create([
            'id_tipo_ident' =>'SD',
            'descripcion' => 'NO DEFINIDO'
        ]);

    }
}

<?php

use Illuminate\Database\Seeder;
use App\Models\TipoIdentEapb;

class tipoIdentEAPBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoIdentEapb::create([
    		'id_tipo_ident' =>'MU',
    		'descripcion' => 'Municipio'

        ]);

        TipoIdentEapb::create([
    		'id_tipo_ident' =>'DE',
    		'descripcion' => 'Departamento'

        ]);

        TipoIdentEapb::create([
    		'id_tipo_ident' =>'DI',
    		'descripcion' => 'Distrito'

        ]);

        TipoIdentEapb::create([
    		'id_tipo_ident' =>'NI',
    		'descripcion' => 'NIT'

        ]);

        TipoIdentEapb::create([
            'id_tipo_ident' =>'SD',
            'descripcion' => 'NO DEFINIDO'
        ]);

        return true;
    }
}

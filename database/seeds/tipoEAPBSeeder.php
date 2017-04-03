<?php

use Illuminate\Database\Seeder;
use App\Models\TipoEapb;

class tipoEAPBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoEapb::create([
    		'id_tipo_ent' =>'C',
    		'descripcion' => 'EAPB Régimen Contributivo'

        ]);

        TipoEapb::create([
    		'id_tipo_ent' =>'S',
    		'descripcion' => 'EAPB Régimen Subsidiado'

        ]);

        TipoEapb::create([
    		'id_tipo_ent' =>'P',
    		'descripcion' => 'EAPB Régimen Excepción'

        ]);

        TipoEapb::create([
    		'id_tipo_ent' =>'O',
    		'descripcion' => 'EAPB Medicina Prepagada o Plan Complementario'

        ]);

        TipoEapb::create([
    		'id_tipo_ent' =>'N',
    		'descripcion' => 'No asegurado'

        ]);

        return true;
    }
}

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

         TipoEapb::create([
            'id_tipo_ent' =>'2',
            'descripcion' => 'Empresas Social de Salud'
        ]);

        TipoEapb::create([
            'id_tipo_ent' =>'3',
            'descripcion' => 'Direcciones  Territoriales de Salud'
        ]);

        TipoEapb::create([
            'id_tipo_ent' =>'9',
            'descripcion' => 'Transporte Especial'
        ]);

        TipoEapb::create([
            'id_tipo_ent' =>'13',
            'descripcion' => 'Empresas de Emergencias Medicas'
        ]);

        TipoEapb::create([
            'id_tipo_ent' =>'14',
            'descripcion' => 'Empresa Objeto Socal Diferete Salud'
        ]);

        TipoEapb::create([
            'id_tipo_ent' =>'15',
            'descripcion' => 'Empresa Administradora de Resgos Profesionales'
        ]);

        TipoEapb::create([
            'id_tipo_ent' =>'16',
            'descripcion' => 'Cajas de Compensacion'
        ]);

        TipoEapb::create([
            'id_tipo_ent' =>'17',
            'descripcion' => 'Union Temporal en Salud'
        ]);

        TipoEapb::create([
            'id_tipo_ent' =>'18',
            'descripcion' => 'Entidades de Regimen de Excepcion'
        ]);

        TipoEapb::create([
            'id_tipo_ent' =>'',
            'descripcion' => 'No definido'
        ]);

        return true;
    }
}

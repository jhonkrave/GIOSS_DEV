<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class IngresosEgresosHospitalario
 */
class IngresosEgresosHospitalario extends Model
{
    protected $table = 'ingresos_egresos_hospitalarios';

    protected $primaryKey = 'identificacion_egreso_seq';

	public $timestamps = false;

    protected $fillable = [
        'id_registro',
        'fecha_hora_ingreso',
        'fecha_hora_egreso',
        'cod_diagnostico_ingreso',
        'cod_diagnostico_egreso',
        'cod_diagnostico_egreso_rel1',
        'cod_diagnostico_egreso_rel2',
        'estado_salida',
        'codigo_diagnóstico_muerte'
    ];

    protected $guarded = [];

        
}
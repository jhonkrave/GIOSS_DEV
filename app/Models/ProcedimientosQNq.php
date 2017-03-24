<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProcedimientosQNq
 */
class ProcedimientosQNq extends Model
{
    protected $table = 'procedimientos_q_nq';

    protected $primaryKey = 'identificacion_consult_seq';

	public $timestamps = false;

    protected $fillable = [
        'id_registro',
        'fecha_procedimiento',
        'tipo_codificacion',
        'cod_procedimiento',
        'cod_diagnostico_principal',
        'cod_diagnostico_rel1',
        'cod_diagnostico_rel2',
        'ambito_procedimiento'
    ];

    protected $guarded = [];

        
}
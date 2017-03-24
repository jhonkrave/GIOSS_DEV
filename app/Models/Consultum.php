<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Consultum
 */
class Consultum extends Model
{
    protected $table = 'consulta';

    protected $primaryKey = 'identificacion_consult_seq';

	public $timestamps = false;

    protected $fillable = [
        'id_registro',
        'fecha_consulta',
        'ambito_consulta',
        'tipo_codificacion',
        'cod_consulta',
        'cod_consulta_esp',
        'cod_diagnostico_principal',
        'cod_diagnostico_rel1',
        'cod_diagnostico_rel2',
        'tipo_diagnostico_principal',
        'finalidad_consulta'
    ];

    protected $guarded = [];

        
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProcedimientoCup
 */
class ProcedimientoCup extends Model
{
    protected $table = 'procedimiento_cups';

    protected $primaryKey = 'cod_procedimiento';

	public $timestamps = false;

    protected $fillable = [
        'descripcion',
        'cod_sis_cups',
        'descrip_sis_cups',
        'cod_grup_cups',
        'desc_grup_cups',
        'ambito_cups',
        'sexo_cups',
        'nivel_atencion'
    ];

    protected $guarded = [];

        
}
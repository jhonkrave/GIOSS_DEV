<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProcedimientoHomologo
 */
class ProcedimientoHomologo extends Model
{
    protected $table = 'procedimiento_homologo';

    protected $primaryKey = 'cod_procedimiento';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
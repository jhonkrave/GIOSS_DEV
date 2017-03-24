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
        'descripcion'
    ];

    protected $guarded = [];

        
}
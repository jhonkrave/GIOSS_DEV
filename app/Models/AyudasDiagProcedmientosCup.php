<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AyudasDiagProcedmientosCup
 */
class AyudasDiagProcedmientosCup extends Model
{
    protected $table = 'ayudas_diag_procedmientos_cups';

    protected $primaryKey = 'cod_procedimiento';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AyudasDiagProcedmientosHomologo
 */
class AyudasDiagProcedmientosHomologo extends Model
{
    protected $table = 'ayudas_diag_procedmientos_homologo';

    protected $primaryKey = 'cod_procedimiento';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
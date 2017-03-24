<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoDiagnostico
 */
class TipoDiagnostico extends Model
{
    protected $table = 'tipo_diagnostico';

    protected $primaryKey = 'cod_tipo';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
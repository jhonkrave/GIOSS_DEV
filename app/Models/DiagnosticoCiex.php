<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DiagnosticoCiex
 */
class DiagnosticoCiex extends Model
{
    protected $table = 'diagnostico_ciex';

    protected $primaryKey = 'cod_diagnostico';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
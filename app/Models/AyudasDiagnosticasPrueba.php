<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AyudasDiagnosticasPrueba
 */
class AyudasDiagnosticasPrueba extends Model
{
    protected $table = 'ayudas_diagnosticas_pruebas';

    protected $primaryKey = 'id_prueba';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
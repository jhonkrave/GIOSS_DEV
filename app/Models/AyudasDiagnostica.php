<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AyudasDiagnostica
 */
class AyudasDiagnostica extends Model
{
    protected $table = 'ayudas_diagnosticas';

    protected $primaryKey = 'id_seq';

	public $timestamps = false;

    protected $fillable = [
        'id_registro',
        'ambito',
        'tipo_prueba',
        'tipo_codificacion',
        'cod_procedimiento',
        'fecha_realizacion',
        'fecha_entrega',
        'resultado'
    ];

    protected $guarded = [];

        
}
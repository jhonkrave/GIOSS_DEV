<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Resultado
 */
class Resultado extends Model
{
    protected $table = 'resultados';

    public $timestamps = false;

    protected $fillable = [
        'ident_resultados_seq',
        'fecha_toma',
        'fecha_resultado',
        'cups_cod',
        'valor_resultado',
        'referencia_usuario'
    ];

    protected $guarded = [];

        
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Medicamento
 */
class Medicamento extends Model
{
    protected $table = 'medicamento';

    protected $primaryKey = 'ident_medicamento_seq';

	public $timestamps = false;

    protected $fillable = [
        'id_registro',
        'fecha_entrega',
        'tipo_codificacion',
        'codigo_medicamento',
        'catidad',
        'ambito_suministro'
    ];

    protected $guarded = [];

        
}
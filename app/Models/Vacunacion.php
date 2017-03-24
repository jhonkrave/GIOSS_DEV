<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Vacunacion
 */
class Vacunacion extends Model
{
    protected $table = 'vacunacion';

    protected $primaryKey = 'ident_vacunacion_seq';

	public $timestamps = false;

    protected $fillable = [
        'id_registro',
        'fecha_aplicacion',
        'tipo_codificacion',
        'codigo_tipo_vacuna',
        'numero_dosis'
    ];

    protected $guarded = [];

        
}
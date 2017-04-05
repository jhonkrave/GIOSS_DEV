<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GiossArchivoAvaCfvl
 */
class GiossArchivoAvaCfvl extends Model
{
    protected $table = 'gioss_archivo_ava_cfvl';

    public $timestamps = false;

    protected $fillable = [
        'fecha_periodo_inicio',
        'fecha_periodo_fin',
        'nombre_archivo',
        'numero_registro',
        'contenido_registro_validado',
        'fecha_hora_validacion'
    ];

    protected $guarded = [];

        
}
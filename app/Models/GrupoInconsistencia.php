<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GrupoInconsistencia
 */
class GrupoInconsistencia extends Model
{
    protected $table = 'grupo_inconsistencias';

    protected $primaryKey = 'cod_grupo';

	public $timestamps = false;

    protected $fillable = [
        'descripcion',
        'cod_tipo'
    ];

    protected $guarded = [];

        
}
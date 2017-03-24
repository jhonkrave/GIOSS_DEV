<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoInconsistencia
 */
class TipoInconsistencia extends Model
{
    protected $table = 'tipo_inconsistencias';

    protected $primaryKey = 'cod_tipo';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
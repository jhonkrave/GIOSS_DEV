<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoEntidad
 */
class TipoEntidad extends Model
{
    protected $table = 'tipo_entidad';

    protected $primaryKey = 'codigo_tipo_entidad';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
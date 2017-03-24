<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoEntidad
 */
class TipoEntidad extends Model
{
    protected $table = 'tipo_entidad';

    protected $primaryKey = 'id_tipo_ent';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
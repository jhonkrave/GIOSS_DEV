<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoIdentificacionEntidad
 */
class TipoIdentificacionEntidad extends Model
{
    protected $table = 'tipo_identificacion_entidad';

    protected $primaryKey = 'id_tipo_ident';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
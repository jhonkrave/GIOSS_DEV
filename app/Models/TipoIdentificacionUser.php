<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoIdentificacionUser
 */
class TipoIdentificacionUser extends Model
{
    protected $table = 'tipo_identificacion_user';

    protected $primaryKey = 'id_tipo_ident';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
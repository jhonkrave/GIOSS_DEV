<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoEapb
 */
class TipoEapb extends Model
{
    protected $table = 'tipo_eapb';

    protected $primaryKey = 'id_tipo_ent';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
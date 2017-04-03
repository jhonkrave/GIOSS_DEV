<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoIdentEapb
 */
class TipoIdentEapb extends Model
{
    protected $table = 'tipo_ident_eapb';

    protected $primaryKey = 'id_tipo_ident';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
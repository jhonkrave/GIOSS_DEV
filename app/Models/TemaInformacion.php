<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TemaInformacion
 */
class TemaInformacion extends Model
{
    protected $table = 'tema_informacion';

    protected $primaryKey = 'id_tema_informacion';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
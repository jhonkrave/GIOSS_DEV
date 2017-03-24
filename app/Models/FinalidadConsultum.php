<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FinalidadConsultum
 */
class FinalidadConsultum extends Model
{
    protected $table = 'finalidad_consulta';

    protected $primaryKey = 'cod_finalidad';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
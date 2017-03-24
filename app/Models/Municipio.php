<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Municipio
 */
class Municipio extends Model
{
    protected $table = 'municipios';

    protected $primaryKey = 'cod_divipola';

	public $timestamps = false;

    protected $fillable = [
        'cod_depto',
        'nombre'
    ];

    protected $guarded = [];

        
}
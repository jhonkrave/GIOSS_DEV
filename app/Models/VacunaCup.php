<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VacunaCup
 */
class VacunaCup extends Model
{
    protected $table = 'vacuna_cups';

    protected $primaryKey = 'codigo_tipo_vacuna';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
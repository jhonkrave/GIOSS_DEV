<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VacunaHomologo
 */
class VacunaHomologo extends Model
{
    protected $table = 'vacuna_homologo';

    protected $primaryKey = 'codigo_tipo_vacuna';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
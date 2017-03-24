<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MedicamentosAtc
 */
class MedicamentosAtc extends Model
{
    protected $table = 'medicamentos_atc';

    protected $primaryKey = 'codigo_medicamento';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
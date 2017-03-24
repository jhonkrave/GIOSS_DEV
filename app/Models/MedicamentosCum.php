<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MedicamentosCum
 */
class MedicamentosCum extends Model
{
    protected $table = 'medicamentos_cum';

    protected $primaryKey = 'codigo_medicamento';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
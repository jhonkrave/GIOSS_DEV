<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MedicamentosR
 */
class MedicamentosR extends Model
{
    protected $table = 'medicamentos_rs';

    protected $primaryKey = 'codigo_medicamento';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MedicamentosHomologo
 */
class MedicamentosHomologo extends Model
{
    protected $table = 'medicamentos_homologo';

    protected $primaryKey = 'codigo_medicamento';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
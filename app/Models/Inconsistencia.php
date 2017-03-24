<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Inconsistencia
 */
class Inconsistencia extends Model
{
    protected $table = 'inconsistencias';

    protected $primaryKey = 'cod_inconsistencia';

	public $timestamps = false;

    protected $fillable = [
        'descripcion',
        'cod_grupo'
    ];

    protected $guarded = [];

        
}
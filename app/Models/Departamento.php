<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Departamento
 */
class Departamento extends Model
{
    protected $table = 'departamentos';

    protected $primaryKey = 'cod_divipola';

	public $timestamps = false;

    protected $fillable = [
        'nombre'
    ];

    protected $guarded = [];

        
}
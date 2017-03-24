<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ConsultaCup
 */
class ConsultaCup extends Model
{
    protected $table = 'consulta_cups';

    protected $primaryKey = 'cod_consulta';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
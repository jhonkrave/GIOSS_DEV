<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ConsultaEspecializacion
 */
class ConsultaEspecializacion extends Model
{
    protected $table = 'consulta_especializacion';

    protected $primaryKey = 'cod_consulta_esp';

	public $timestamps = false;

    protected $fillable = [
        'descrip_consulta_esp'
    ];

    protected $guarded = [];

        
}
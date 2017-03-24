<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ConsultaHomologo
 */
class ConsultaHomologo extends Model
{
    protected $table = 'consulta_homologo';

    protected $primaryKey = 'cod_consulta';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
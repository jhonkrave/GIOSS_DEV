<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PesoTallaTension
 */
class PesoTallaTension extends Model
{
    protected $table = 'peso_talla_tension';

    protected $primaryKey = 'id_seq';

	public $timestamps = false;

    protected $fillable = [
        'id_registro',
        'ambito',
        'fecha_med_peso',
        'valor_peso',
        'fecha_med_talla',
        'valor_talla',
        'fecha_med_tension',
        'valor_tension_sistolica',
        'valor_tension_diastolica'
    ];

    protected $guarded = [];

        
}
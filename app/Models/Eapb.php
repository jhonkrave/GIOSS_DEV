<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Eapb
 */
class Eapb extends Model
{
    protected $table = 'eapbs';

    protected $primaryKey = 'id_entidad';

	public $timestamps = false;

    protected $fillable = [
        'tipo_entidad',
        'tipo_identificacion',
        'num_identificacion',
        'cod_eapb',
        'nombre',
        'cod_mpio'
    ];

    protected $guarded = [];

        
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EntidadesSectorSalud
 */
class EntidadesSectorSalud extends Model
{
    protected $table = 'entidades_sector_salud';

    protected $primaryKey = 'id_entidad';

	public $timestamps = false;

    protected $fillable = [
        'cod_tipo_entidad',
        'nombre_de_la_entidad',
        'cod_mpio',
        'num_identificacion',
        'cod_habilitacion'
    ];

    protected $guarded = [];

        
}
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
        'tipo_entidad',
        'tipo_identificacion',
        'num_identificacion',
        'cod_habilitacion',
        'nombre',
        'cod_mpio'
    ];

    protected $guarded = [];

        
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DIdentificacionUsrIp
 */
class DIdentificacionUsrIp extends Model
{
    protected $table = 'd_identificacion_usr_ips';

    protected $primaryKey = 'identificacion_seq';

	public $timestamps = false;

    protected $fillable = [
        'fecha_ingreso',
        'tipo_identificacion',
        'numero_identenficacion',
        'fecha_nacimiento',
        'sexo',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'regimen',
        'cod_prestador_servicios_salud'
    ];

    protected $guarded = [];

        
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserIp
 */
class UserIp extends Model
{
    protected $table = 'user_ips';

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'fecha_ingreso',
        'tipo_identificacion',
        'num_identenficacion',
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
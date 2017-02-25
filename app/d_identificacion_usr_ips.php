<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $identificacion_seq
 * @property string $cod_prestador_servicios_salud
 * @property string $fecha_ingreso
 * @property string $tipo_identificacion
 * @property string $numero_identenficacion
 * @property string $fecha_nacimiento
 * @property string $sexo
 * @property string $primer_nombre
 * @property string $segundo_nombre
 * @property string $primer_apellido
 * @property string $segundo_apellido
 * @property string $regimen
 * @property EntidadesSectorSalud $entidadesSectorSalud
 * @property Resultado[] $resultados
 * @property Registro[] $registros
 */
class d_identificacion_usr_ips extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['cod_prestador_servicios_salud', 'fecha_ingreso', 'tipo_identificacion', 'numero_identenficacion', 'fecha_nacimiento', 'sexo', 'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'regimen'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entidadesSectorSalud()
    {
        return $this->belongsTo('App\EntidadesSectorSalud', 'cod_prestador_servicios_salud', 'codigo_entidad');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resultados()
    {
        return $this->hasMany('App\Resultado', 'referencia_usuario', 'identificacion_seq');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registros()
    {
        return $this->hasMany('App\Registro', 'id_user', 'identificacion_seq');
    }
}

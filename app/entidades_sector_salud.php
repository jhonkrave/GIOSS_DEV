<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $codigo_entidad
 * @property integer $cod_tipo_entidad
 * @property string $nombre_de_la_entidad
 * @property string $codigo_dpto
 * @property string $cod_mpio
 * @property string $des_tipo_entidad_salud
 * @property string $numero_identificacion
 * @property string $digito_verificacion
 * @property TipoEntidad $tipoEntidad
 * @property Archivo[] $archivos
 * @property DIdentificacionUsrIp[] $dIdentificacionUsrIps
 */
class entidades_sector_salud extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'entidades_sector_salud';

    /**
     * @var array
     */
    protected $fillable = ['cod_tipo_entidad', 'nombre_de_la_entidad', 'codigo_dpto', 'cod_mpio', 'des_tipo_entidad_salud', 'numero_identificacion', 'digito_verificacion'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipoEntidad()
    {
        return $this->belongsTo('App\TipoEntidad', 'cod_tipo_entidad', 'codigo_tipo_entidad');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function archivos()
    {
        return $this->hasMany('App\Archivo', 'cod_ident_entidad', 'codigo_entidad');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dIdentificacionUsrIps()
    {
        return $this->hasMany('App\DIdentificacionUsrIp', 'cod_prestador_servicios_salud', 'codigo_entidad');
    }
}

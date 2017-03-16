<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id_entidad
 * @property integer $cod_tipo_entidad
 * @property integer $cod_mpio
 * @property string $nombre_de_la_entidad
 * @property integer $num_identificacion
 * @property integer $cod_habilitacion
 * @property TipoEntidad $tipoEntidad
 * @property Municipio $municipio
 * @property UserEntidad[] $userEntidads
 * @property Archivo[] $archivos
 * @property DIdentificacionUsrIp[] $dIdentificacionUsrIps
 */
class entidades_sector_salud extends Model
{
    /**
    * primary key    
    */


    protected $primaryKey = 'id_entidad';
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'entidades_sector_salud';

    /**
     * @var array
     */
    protected $fillable = ['cod_tipo_entidad', 'cod_mpio', 'nombre_de_la_entidad', 'num_identificacion', 'cod_habilitacion'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipoEntidad()
    {
        return $this->belongsTo('App\TipoEntidad', 'cod_tipo_entidad', 'codigo_tipo_entidad');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function municipio()
    {
        return $this->belongsTo('App\Municipio', 'cod_mpio', 'cod_divipola');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userEntidads()
    {
        return $this->hasMany('App\UserEntidad', 'id_entidad', 'id_entidad');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function archivos()
    {
        return $this->hasMany('App\Archivo', 'id_entidad', 'id_entidad');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dIdentificacionUsrIps()
    {
        return $this->hasMany('App\DIdentificacionUsrIp', 'cod_prestador_servicios_salud', 'id_entidad');
    }
}

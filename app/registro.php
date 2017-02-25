<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id_registro_seq
 * @property integer $id_archivo
 * @property integer $id_user
 * @property Archivo $archivo
 * @property DIdentificacionUsrIp $dIdentificacionUsrIp
 * @property ProcedimientosQNq[] $procedimientosQNqs
 * @property Consultum[] $consultas
 * @property IngresosEgresosHospitalario[] $ingresosEgresosHospitalarios
 * @property Medicamento[] $medicamentos
 * @property Vacunacion[] $vacunacions
 */
class registro extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'registro';

    /**
     * @var array
     */
    protected $fillable = ['id_archivo', 'id_user'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function archivo()
    {
        return $this->belongsTo('App\Archivo', 'id_archivo', 'id_archivo_seq');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dIdentificacionUsrIp()
    {
        return $this->belongsTo('App\DIdentificacionUsrIp', 'id_user', 'identificacion_seq');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function procedimientosQNqs()
    {
        return $this->hasMany('App\ProcedimientosQNq', 'id_registro', 'id_registro_seq');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function consultas()
    {
        return $this->hasMany('App\Consultum', 'id_registro', 'id_registro_seq');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ingresosEgresosHospitalarios()
    {
        return $this->hasMany('App\IngresosEgresosHospitalario', 'id_registro', 'id_registro_seq');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medicamentos()
    {
        return $this->hasMany('App\Medicamento', 'id_registro', 'id_registro_seq');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vacunacions()
    {
        return $this->hasMany('App\Vacunacion', 'id_registro', 'id_registro_seq');
    }
}

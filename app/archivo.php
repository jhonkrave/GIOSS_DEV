<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id_archivo_seq
 * @property string $id_tema_informacion
 * @property string $cod_ident_entidad
 * @property string $modulo_informacion
 * @property integer $tipo_fuente
 * @property string $tipo_periodo
 * @property string $fecha_ini_periodo
 * @property string $fecha_fin_periodo
 * @property integer $cod_habilitacion_entidad
 * @property integer $numero_registros
 * @property TemaInformacion $temaInformacion
 * @property EntidadesSectorSalud $entidadesSectorSalud
 * @property Registro[] $registros
 */
class archivo extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'archivo';

    /**
     * @var array
     */
    protected $fillable = ['id_tema_informacion', 'cod_ident_entidad', 'modulo_informacion', 'tipo_fuente', 'tipo_periodo', 'fecha_ini_periodo', 'fecha_fin_periodo', 'cod_habilitacion_entidad', 'numero_registros'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function temaInformacion()
    {
        return $this->belongsTo('App\TemaInformacion', 'id_tema_informacion', 'id_tema_informacion');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entidadesSectorSalud()
    {
        return $this->belongsTo('App\EntidadesSectorSalud', 'cod_ident_entidad', 'codigo_entidad');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registros()
    {
        return $this->hasMany('App\Registro', 'id_archivo', 'id_archivo_seq');
    }
}

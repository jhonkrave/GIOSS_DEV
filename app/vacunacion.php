<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $ident_vacunacion_seq
 * @property integer $id_registro
 * @property string $fecha_aplicacion
 * @property integer $tipo_codificacion
 * @property string $codigo_tipo_vacuna
 * @property integer $numero_dosis
 * @property Registro $registro
 */
class vacunacion extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'vacunacion';

    /**
     * @var array
     */
    protected $fillable = ['id_registro', 'fecha_aplicacion', 'tipo_codificacion', 'codigo_tipo_vacuna', 'numero_dosis'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function registro()
    {
        return $this->belongsTo('App\Registro', 'id_registro', 'id_registro_seq');
    }
}

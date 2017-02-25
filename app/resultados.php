<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $referencia_usuario
 * @property integer $ident_resultados_seq
 * @property string $fecha_toma
 * @property string $fecha_resultado
 * @property string $cups_cod
 * @property float $valor_resultado
 * @property DIdentificacionUsrIp $dIdentificacionUsrIp
 */
class resultados extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['referencia_usuario', 'ident_resultados_seq', 'fecha_toma', 'fecha_resultado', 'cups_cod', 'valor_resultado'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dIdentificacionUsrIp()
    {
        return $this->belongsTo('App\DIdentificacionUsrIp', 'referencia_usuario', 'identificacion_seq');
    }
}

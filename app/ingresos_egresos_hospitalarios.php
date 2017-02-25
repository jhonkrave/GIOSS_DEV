<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $identificacion_egreso_seq
 * @property integer $id_registro
 * @property string $cod_diagnostico_ingreso
 * @property string $cod_diagnostico_egreso
 * @property string $cod_diagnostico_egreso_rel1
 * @property string $cod_diagnostico_egreso_rel2
 * @property string $fecha_hora_ingreso
 * @property string $fecha_hora_egreso
 * @property integer $estado_salida
 * @property string $codigo_diagnóstico_muerte
 * @property Registro $registro
 * @property DiagnosticoCiex $diagnosticoCiex
 * @property DiagnosticoCiex $diagnosticoCiex
 * @property DiagnosticoCiex $diagnosticoCiex
 * @property DiagnosticoCiex $diagnosticoCiex
 * @property DiagnosticoCiex $diagnosticoCiex
 */
class ingresos_egresos_hospitalarios extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['id_registro', 'cod_diagnostico_ingreso', 'cod_diagnostico_egreso', 'cod_diagnostico_egreso_rel1', 'cod_diagnostico_egreso_rel2', 'fecha_hora_ingreso', 'fecha_hora_egreso', 'estado_salida', 'codigo_diagnóstico_muerte'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function registro()
    {
        return $this->belongsTo('App\Registro', 'id_registro', 'id_registro_seq');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function diagnosticoCiex()
    {
        return $this->belongsTo('App\DiagnosticoCiex', 'cod_diagnostico_ingreso', 'cod_diagnostico');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function diagnosticoCiex()
    {
        return $this->belongsTo('App\DiagnosticoCiex', 'cod_diagnostico_egreso', 'cod_diagnostico');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function diagnosticoCiex()
    {
        return $this->belongsTo('App\DiagnosticoCiex', 'cod_diagnostico_egreso_rel1', 'cod_diagnostico');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function diagnosticoCiex()
    {
        return $this->belongsTo('App\DiagnosticoCiex', 'cod_diagnostico_egreso_rel2', 'cod_diagnostico');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function diagnosticoCiex()
    {
        return $this->belongsTo('App\DiagnosticoCiex', '"codigo_diagnóstico_muerte"', 'cod_diagnostico');
    }
}

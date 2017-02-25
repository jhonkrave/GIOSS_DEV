<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $identificacion_consult_seq
 * @property integer $id_registro
 * @property integer $cod_consulta_esp
 * @property string $cod_diagnostico_principal
 * @property string $cod_diagnostico_rel1
 * @property string $cod_diagnostico_rel2
 * @property string $ambito_consulta
 * @property integer $finalidad_consulta
 * @property string $fecha_consulta
 * @property integer $tipo_codificacion
 * @property string $cod_consulta
 * @property integer $tipo_diagnostico_principal
 * @property Registro $registro
 * @property ConsultaEspecializacion $consultaEspecializacion
 * @property DiagnosticoCiex $diagnosticoCiex
 * @property DiagnosticoCiex $diagnosticoCiex
 * @property DiagnosticoCiex $diagnosticoCiex
 * @property Ambito $ambito
 * @property FinalidadConsultum $finalidadConsultum
 */
class consulta extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'consulta';

    /**
     * @var array
     */
    protected $fillable = ['id_registro', 'cod_consulta_esp', 'cod_diagnostico_principal', 'cod_diagnostico_rel1', 'cod_diagnostico_rel2', 'ambito_consulta', 'finalidad_consulta', 'fecha_consulta', 'tipo_codificacion', 'cod_consulta', 'tipo_diagnostico_principal'];

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
    public function consultaEspecializacion()
    {
        return $this->belongsTo('App\ConsultaEspecializacion', 'cod_consulta_esp', 'cod_consulta_esp');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function diagnosticoCiex()
    {
        return $this->belongsTo('App\DiagnosticoCiex', 'cod_diagnostico_principal', 'cod_diagnostico');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function diagnosticoCiex()
    {
        return $this->belongsTo('App\DiagnosticoCiex', 'cod_diagnostico_rel1', 'cod_diagnostico');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function diagnosticoCiex()
    {
        return $this->belongsTo('App\DiagnosticoCiex', 'cod_diagnostico_rel2', 'cod_diagnostico');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ambito()
    {
        return $this->belongsTo('App\Ambito', 'ambito_consulta', 'cod_ambito');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function finalidadConsultum()
    {
        return $this->belongsTo('App\FinalidadConsultum', 'finalidad_consulta', 'cod_finalidad');
    }
}

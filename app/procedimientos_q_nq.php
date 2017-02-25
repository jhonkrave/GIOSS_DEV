<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $identificacion_consult_seq
 * @property integer $id_registro
 * @property string $cod_diagnostico_principal
 * @property string $cod_diagnostico_rel1
 * @property string $cod_diagnostico_rel2
 * @property string $ambito_procedimiento
 * @property string $fecha_procedimiento
 * @property integer $tipo_codificacion
 * @property string $cod_procedimiento
 * @property Registro $registro
 * @property DiagnosticoCiex $diagnosticoCiex
 * @property DiagnosticoCiex $diagnosticoCiex
 * @property DiagnosticoCiex $diagnosticoCiex
 * @property Ambito $ambito
 */
class procedimientos_q_nq extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'procedimientos_q_nq';

    /**
     * @var array
     */
    protected $fillable = ['id_registro', 'cod_diagnostico_principal', 'cod_diagnostico_rel1', 'cod_diagnostico_rel2', 'ambito_procedimiento', 'fecha_procedimiento', 'tipo_codificacion', 'cod_procedimiento'];

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
        return $this->belongsTo('App\Ambito', 'ambito_procedimiento', 'cod_ambito');
    }
}

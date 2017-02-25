<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $ident_medicamento_seq
 * @property integer $id_registro
 * @property string $ambito_suministro
 * @property string $fecha_entrega
 * @property integer $tipo_codificacion
 * @property string $codigo_medicamento
 * @property integer $catidad
 * @property Registro $registro
 * @property Ambito $ambito
 */
class medicamento extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'medicamento';

    /**
     * @var array
     */
    protected $fillable = ['id_registro', 'ambito_suministro', 'fecha_entrega', 'tipo_codificacion', 'codigo_medicamento', 'catidad'];

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
    public function ambito()
    {
        return $this->belongsTo('App\Ambito', 'ambito_suministro', 'cod_ambito');
    }
}

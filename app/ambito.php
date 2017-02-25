<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $cod_ambito
 * @property string $descripcion
 * @property ProcedimientosQNq[] $procedimientosQNqs
 * @property Consultum[] $consultas
 * @property Medicamento[] $medicamentos
 */
class ambito extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ambito';

    /**
     * @var array
     */
    protected $fillable = ['descripcion'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function procedimientosQNqs()
    {
        return $this->hasMany('App\ProcedimientosQNq', 'ambito_procedimiento', 'cod_ambito');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function consultas()
    {
        return $this->hasMany('App\Consultum', 'ambito_consulta', 'cod_ambito');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medicamentos()
    {
        return $this->hasMany('App\Medicamento', 'ambito_suministro', 'cod_ambito');
    }
}

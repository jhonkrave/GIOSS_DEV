<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $cod_consulta_esp
 * @property string $descrip_consulta_esp
 * @property Consultum[] $consultas
 */
class consulta_especializacion extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'consulta_especializacion';

    /**
     * @var array
     */
    protected $fillable = ['descrip_consulta_esp'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function consultas()
    {
        return $this->hasMany('App\Consultum', 'cod_consulta_esp', 'cod_consulta_esp');
    }
}

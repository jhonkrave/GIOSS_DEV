<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $cod_divipola
 * @property integer $cod_depto
 * @property string $nombre
 * @property Departamento $departamento
 * @property EntidadesSectorSalud[] $entidadesSectorSaluds
 */
class municipio extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['cod_depto', 'nombre'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function departamento()
    {
        return $this->belongsTo('App\Departamento', 'cod_depto', 'cod_divipola');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entidadesSectorSaluds()
    {
        return $this->hasMany('App\EntidadesSectorSalud', 'cod_mpio', 'cod_divipola');
    }
}

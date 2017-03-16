<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $cod_divipola
 * @property string $nombre
 * @property Municipio[] $municipios
 */
class departamento extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['nombre'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function municipios()
    {
        return $this->hasMany('App\Municipio', 'cod_depto', 'cod_divipola');
    }
}

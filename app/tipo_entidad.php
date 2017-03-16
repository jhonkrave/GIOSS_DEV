<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $codigo_tipo_entidad
 * @property string $descripcion
 * @property EntidadesSectorSalud[] $entidadesSectorSaluds
 */
class tipo_entidad extends Model
{
    //para bloquear los campos timestamp
    public $timestamps = false;
    //para asignar primary key determinadad. Por dfecto la primerary key esla columna id
    protected $primaryKey = 'codigo_tipo_entidad';
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'tipo_entidad';

    /**
     * @var array
     */
    protected $fillable = ['descripcion'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entidadesSectorSaluds()
    {
        return $this->hasMany('App\EntidadesSectorSalud', 'cod_tipo_entidad', 'codigo_tipo_entidad');
    }
}

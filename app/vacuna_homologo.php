<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $codigo_tipo_vacuna
 * @property string $descripcion
 */
class vacuna_homologo extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'vacuna_homologo';

    /**
     * @var array
     */
    protected $fillable = ['descripcion'];

}

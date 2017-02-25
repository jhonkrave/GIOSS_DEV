<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $codigo_medicamento
 * @property string $descripcion
 */
class medicamentos_homologo extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'medicamentos_homologo';

    /**
     * @var array
     */
    protected $fillable = ['descripcion'];

}

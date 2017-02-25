<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $cod_procedimiento
 * @property string $descripcion
 */
class procedimiento_homologo extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'procedimiento_homologo';

    /**
     * @var array
     */
    protected $fillable = ['descripcion'];

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $cod_consulta
 * @property string $descripcion
 */
class consulta_homologo extends Model
{

    public $timestamps = false;
    public $primaryKey  = 'cod_consulta';

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'consulta_homologo';

    /**
     * @var array
     */
    protected $fillable = ['descripcion'];

}

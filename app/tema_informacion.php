<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id_tema_informacion
 * @property string $descripcion
 * @property Archivo[] $archivos
 */
class tema_informacion extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'tema_informacion';

    /**
     * @var array
     */
    protected $fillable = ['descripcion'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function archivos()
    {
        return $this->hasMany('App\Archivo', 'id_tema_informacion', 'id_tema_informacion');
    }
}

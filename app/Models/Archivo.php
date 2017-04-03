<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Archivo
 */
class Archivo extends Model
{
    protected $table = 'archivo';

    public $primaryKey = 'id_archivo_seq';

	public $timestamps = false;

    protected $fillable = [
        'modulo_informacion',
        'tipo_fuente',
        'id_tema_informacion',
        'tipo_periodo',
        'fecha_ini_periodo',
        'fecha_fin_periodo',
        'id_entidad',
        'numero_registros'
    ];

    protected $guarded = [];

        
}
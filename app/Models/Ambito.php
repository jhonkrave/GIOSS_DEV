<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Ambito
 */
class Ambito extends Model
{
    protected $table = 'ambito';

    protected $primaryKey = 'cod_ambito';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
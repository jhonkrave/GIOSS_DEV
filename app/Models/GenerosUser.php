<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GenerosUser
 */
class GenerosUser extends Model
{
    protected $table = 'generos_user';

    protected $primaryKey = 'id_genero';

	public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    protected $guarded = [];

        
}
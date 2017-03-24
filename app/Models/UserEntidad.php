<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserEntidad
 */
class UserEntidad extends Model
{
    protected $table = 'user_entidads';

    public $timestamps = true;

    protected $fillable = [
        'userid',
        'id_entidad'
    ];

    protected $guarded = [];

        
}
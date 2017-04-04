<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Registro
 */
class Registro extends Model
{
    protected $table = 'registro';

    protected $primaryKey = 'id_registro_seq';

	public $timestamps = false;

    protected $fillable = [
        'id_archivo',
        'id_user',
        'id_eapb'
    ];

    protected $guarded = [];

        
}
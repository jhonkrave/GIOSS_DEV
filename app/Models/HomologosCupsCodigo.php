<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class HomologosCupsCodigo
 */
class HomologosCupsCodigo extends Model
{
    protected $table = 'homologos_cups_codigos';

    protected $primaryKey = 'id_seq';

	public $timestamps = false;

    protected $fillable = [
        'cod_homologo',
        'cod_cups'
    ];

    protected $guarded = [];

        
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class user_entidad extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'user_entidads';

    /**
     * @var array
     */
    protected $fillable = ['id_entidad','userid'];

}

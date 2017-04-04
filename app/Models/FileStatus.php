<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FileStatus
 */
class FileStatus extends Model
{
    protected $table = 'file_statuses';
    public $primaryKey = 'file_statuses_id';

    public $timestamps = false;

    protected $fillable = [
        'consecutive',
        'filename',
        'type',
        'current_status',
        'final_status',
        'zipath'
    ];

    protected $guarded = [];

        
}
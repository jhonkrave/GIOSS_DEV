<?php

namespace App\Traits;

trait FormatDates
{
    protected $newDateFormat = 'd.m.Y H:i';


    // save the date in UTC format in DB table
    public function setCreatedAtAttribute($date){

        $this->attributes['created_at'] = Carbon::parse($date);

    }

    // convert the UTC format to my format
    public function getCreatedAtAttribute($date){

        return Carbon::parse($date)->format($this->newDateFormat);

    }

    // save the date in UTC format in DB table
    public function setUpdatedAtAttribute($date){

        $this->attributes['updated_at'] = Carbon::parse($date);

    }

    // convert the UTC format to my format
    public function getUpdatedAtAttribute($date){

        return Carbon::parse($date)->format($this->newDateFormat);

    }

    // save the date in UTC format in DB table
    public function setDeletedAtAttribute($date){

        $this->attributes['deleted_at'] = Carbon::parse($date);

    }

    // convert the UTC format to my format
    public function getDeletedAtAttribute($date){

        return Carbon::parse($date)->format($this->newDateFormat);

    }
}
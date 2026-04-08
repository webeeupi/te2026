<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = [
        'building_id',
        'code',
        'name',
        'floor',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
}
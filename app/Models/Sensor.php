<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $fillable = [
        'classroom_id',
        'name',
        'value',
        'unit',
        'last_updated',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
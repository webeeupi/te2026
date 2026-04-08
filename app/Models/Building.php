<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = [
        'code',
        'name',
        'location',
    ];

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }
}
<?php

namespace App\Models\ST;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    //
    use HasFactory;
    protected $fillable = [];
    protected $guarded = [];
    protected $table = 'fetnet_rooms';
}

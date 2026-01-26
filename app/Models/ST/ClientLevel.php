<?php

namespace App\Models\ST;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientLevel extends Model
{
    //
    use HasFactory;
    protected $fillable = [];
    protected $guarded = [];
    protected $table = 'fetnet_client_level';
}

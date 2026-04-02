<?php

namespace App\Models\BEMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Client extends Model
{
    use HasFactory;
    protected $table = 'bems_clients';
    protected $guarded = [];
    protected $fillable=[];
    public function user(){
        $this->belongsTo(User::class, 'user_id', 'id');
    }
}

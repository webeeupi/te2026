<?php

namespace App\Models\ST;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //
    use HasFactory;
    protected $fillable = [];
    protected $guarded = [];
    protected $table = 'fetnet_client';

    public function fetuser(){
        return $this->hasOne(FetUser::class, 'user_id','id' );
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id','id' );
    }


    public function level(){
        return $this->belongsTo(ClientLevel::class, 'client_level_id','id' );
    }

    public function cluster(){
        return $this->hasOne(ClusterBase::class, 'client_id','id' );
    }

    public function faculty(){
        return $this->belongsTo(Faculty::class, 'faculty_id','id' );
    }

    public function university(){
        return $this->belongsTo(University::class, 'university_id','id' );
    }

    public function config(){
        return $this->hasOne(ClientConfig::class, 'client_id','id' );
    }
}

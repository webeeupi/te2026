<?php

namespace App\Models\ST;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    //
    use HasFactory;
    protected $fillable = [];
    protected $guarded = [];
    protected $table = 'st_teacher';

    public function user(){
        return $this->belongsTo(User::class, 'user_id','id' );
    }
    public function program(){
        return $this->belongsTo(Program::class, 'program_id','id' );
    }

    public function schedules()
    {
        // Parameter: Model Lawan, Nama Tabel Pivot, FK di Pivot untuk Model Ini, FK di Pivot untuk Model Lawan
        return $this->belongsToMany(Schedule::class, 'st_schedule_teacher', 'teacher_id', 'schedule_id');
    }


}

<?php

namespace App\Models\ST;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ST\Subject;
class Schedule extends Model
{
    //
    use HasFactory;
    protected $fillable = [];
    protected $guarded = [];
    protected $table = 'st_schedule';

    public function program() // <--- Nama ini yang dipanggil di with()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function teachers()
    {
        // Parameter: Model Tujuan, Nama Tabel Pivot, FK di Pivot (parent), FK di Pivot (related)
        return $this->belongsToMany(Teacher::class, 'st_schedule_teacher', 'schedule_id', 'teacher_id')->withTimestamps();
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }


}

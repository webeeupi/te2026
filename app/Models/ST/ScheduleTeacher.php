<?php

namespace App\Models\ST;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleTeacher extends Model
{
    //
    use HasFactory;
    protected $fillable = [];
    protected $guarded = [];
    protected $table = 'st_schedule_teacher';
    /**
     * Relasi ke Tabel Schedule
     * Satu baris data di sini adalah milik satu Jadwal tertentu.
     */
    public function schedule()
    {
        // Parameter ke-2 optional jika nama kolomnya standard (schedule_id)
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    /**
     * Relasi ke Tabel Teacher
     * Satu baris data di sini adalah milik satu Dosen tertentu.
     */
    public function teacher()
    {
        // Parameter ke-2 optional jika nama kolomnya standard (teacher_id)
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }


}

<?php

namespace App\Models\ST;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Model Activities
 *
 * Tabel  : fetnet_activities
 * Relasi : teachers (many‑to‑many), students (many‑to‑many), subject (many‑to‑one)
 */
class Activities extends Model
{
    // Jika semua kolom dapat di‑mass‑assign, cukup kosongkan $guarded.
    protected $guarded = [];

    /** Nama tabel khusus */
    protected $table = 'fetnet_activities';

    /* -----------------------------------------------------------------
     |  Relasi ke Dosen (many‑to‑many) – tabel pivot: fetnet_activity_teachers
     |-----------------------------------------------------------------*/
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(
            Teacher::class,
            'fetnet_activity_teachers',
            'activity_id',
            'teacher_id'
        )->using(ActivityTeacher::class);
    }

    /* -----------------------------------------------------------------
     |  Relasi ke Mahasiswa (many‑to‑many) – tabel pivot: fetnet_activity_students
     |-----------------------------------------------------------------*/
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            Student::class,
            'fetnet_activity_students',
            'activity_id',
            'student_id'
        )->using(ActivityStudent::class);
    }

    /* -----------------------------------------------------------------
     |  ✅ RELASI SUBJECT (many‑to‑one)
     |  Setiap activity **memiliki** satu subject.
     |  Pastikan tabel `fetnet_activities` memiliki kolom `subject_id`
     |  yang menjadi foreign key ke tabel `subjects` (atau `fetnet_subjects`
     |  bila memakai prefix yang sama).
     |-----------------------------------------------------------------*/
    public function subject(): BelongsTo
    {
        return $this->belongsTo(
            Subject::class,      // model Subject (pastikan ada di App\Models\FetNet\Subject)
            'subject_id',        // nama kolom FK di tabel fetnet_activities
            'id'                 // kolom primary key di tabel subjects
        );
    }
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(
            Room::class,
            'fetnet_activity_rooms',
            'activity_id',
            'room_id'
        )->using(ActivityRoom::class);
    }
}

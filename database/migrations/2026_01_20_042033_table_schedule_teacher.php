<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('st_schedule_teacher', function (Blueprint $table) {
            $table->id();

            // PERBAIKAN DI SINI:
            // Tambahkan 'st_schedule' sebagai parameter agar merujuk ke tabel yang benar
            $table->foreignId('schedule_id')->constrained('st_schedule')->onDelete('cascade');

            // Pastikan tabel teacher juga benar namanya.
            // Jika nama tabelnya 'st_teacher', ubah 'teacher' menjadi 'st_teacher'
            $table->foreignId('teacher_id')->constrained('st_teacher')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('st_schedule_teacher');
    }
};

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
        Schema::create('st_schedule', function (Blueprint $table) {
            $table->id();
            $table->integer('program_id')->default(0);

            $table->string('student');
            $table->integer('year');
            $table->string('day');
            $table->time('start');
            $table->time('end');
            $table->string('room')->nullable();

            // --- PERUBAHAN DISINI ---
            // 1. Tambahkan nullable() agar boleh kosong
            $table->integer('subject_id')->nullable();

            // 2. Foreign key dengan nullOnDelete (jika subject dihapus, jadwal jadi null, bukan hilang)
            $table->foreign('subject_id')
                ->references('id')
                ->on('st_subject')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('st_schedule');
    }
};

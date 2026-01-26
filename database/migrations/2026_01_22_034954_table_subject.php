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
        Schema::create('st_subject', function (Blueprint $table) {
            $table->integer('id')->primary()->autoIncrement();
            $table->integer('program_id')->nullable();
            $table->char('code', 10)->nullable();
            $table->char('name', 255)->nullable();
            $table->integer('credit')->nullable();
            $table->integer('semester')->nullable();
            $table->integer('curriculum')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('st_subject');
    }
};

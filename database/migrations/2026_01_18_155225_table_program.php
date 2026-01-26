<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('st_program', function (Blueprint $table) {
            $table->integer('id')->primary()->autoIncrement();
            $table->string('code', 10)->nullable();
            $table->string('abbrev', 10)->nullable();
            $table->string('name', 50)->nullable();
            $table->string('letter_code')->nullable();
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('st_program');
    }
};

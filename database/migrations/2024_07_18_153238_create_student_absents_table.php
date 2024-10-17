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
        Schema::create('student_absents', function (Blueprint $table) {
            $table->id();
            $table->date('rec_date');
            $table->string('nik');
            // Kehadiran => 1 = hadir, 2 = izin sakit, 3 = izin, 4 = tanpa keterangan
            $table->tinyInteger('kehadiran');
            $table->string('note')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_absents');
    }
};

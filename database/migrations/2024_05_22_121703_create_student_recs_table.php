<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_recs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_nik');
            $table->date('rec_date');
            $table->string('rec_times');
            $table->string('rec_detail');
            $table->string('rec_sum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_recs');
    }
};
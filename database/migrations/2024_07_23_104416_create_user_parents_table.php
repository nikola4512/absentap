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
        Schema::create('user_parents', function (Blueprint $table) {
            $table->id();
            // $table->string('name')->unique();
            $table->string('name');
            $table->string('nik')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('active')->default(1);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_parents');
    }
};

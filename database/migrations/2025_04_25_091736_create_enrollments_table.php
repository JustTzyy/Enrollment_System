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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->string('gradeLevel');
            $table->string('semester');
            $table->foreignId('userID')->constrained('users')->onDelete('cascade');
            $table->foreignId('schoolYearID')->constrained('school_years')->onDelete('cascade');
            $table->foreignId('sectionID')->constrained('sections')->onDelete('cascade');
            $table->foreignId('strandID')->constrained('strands')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};

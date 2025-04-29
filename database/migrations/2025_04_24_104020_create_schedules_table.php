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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

            $table->string('day');
            $table->time('startTime');
            $table->time('endTime');
        
            $table->foreignId('userID')->constrained('users')->onDelete('cascade')->nunnable();
            $table->foreignId('sectionID')->constrained('sections')->onDelete('cascade');
            $table->foreignId('subjectAssignmentID')->constrained('subject_assignments')->onDelete('cascade');
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};

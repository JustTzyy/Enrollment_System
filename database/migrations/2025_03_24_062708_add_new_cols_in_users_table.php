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
        Schema::table('users', function (Blueprint $table) {
            $table->string('firstName');
            $table->string('middleName')->nullable();
            $table->string('lastName');
            $table->unsignedTinyInteger('age');
            $table->date('birthday');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('contactNumber', 15);
            $table->enum('status', ['active', 'inactive', 'Grade 11', 'Grade 12', 'Highschool Graduate'])->default('active');
            $table->boolean('archive')->default(false);
            $table->unsignedBigInteger(column: 'roleID');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('roleID')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};

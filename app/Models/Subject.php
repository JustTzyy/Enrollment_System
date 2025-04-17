<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes;    

    protected $table = 'subjects'; 

    protected $fillable = ['subject', 'description', 'type']; 

    public function teacherAssignment()
    {
        return $this->hasMany(TeacherAssignment::class, 'subjectID');
    }

    public function subjectAssignment()
    {
        return $this->hasMany(SubjectAssignment::class, 'subjectID');
    }

    
}

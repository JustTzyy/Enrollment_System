<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherAssignment extends Model
{
    use HasFactory, SoftDeletes;    

    protected $table = 'teacher_assignments'; 

    protected $fillable = ['code', 'subjectID', 'userID']; 

    public function user()
    {
        return $this->belongsTo(User::class,  'userID');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subjectID');
    }

    public function subjectAssignment()
{
    return $this->belongsTo(SubjectAssignment::class, 'subjectAssignmentID');  // adjust the foreign key if needed
}


}

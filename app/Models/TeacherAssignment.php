<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherAssignment extends Model
{
    use HasFactory, SoftDeletes;    

    protected $table = 'teacher_assignments'; 

    protected $fillable = ['title', 'time', 'subjectID', 'userID']; 

    public function user()
    {
        return $this->belongsTo(User::class,  'userID');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subjectID');
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'day',
        'startTime',
        'endTime',
        'userID',
        'sectionID',
        'subjectAssignmentID',
        
    ];

    protected $casts = [
        'startTime' => 'datetime:H:i',
        'endTime'   => 'datetime:H:i',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'sectionID');
    }

    public function subjectAssignment()
    {
        return $this->belongsTo(SubjectAssignment::class, 'subjectAssignmentID');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'sectionID');
    }
}

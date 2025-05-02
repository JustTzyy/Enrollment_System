<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $table = 'enrollments';

    protected $fillable = ['gradeLevel','userID','schoolYearID','sectionID','strandID', 'semester' ];

    public function user() {
        return $this->belongsTo(User::class, 'userID');
    }

    public function section() {
        return $this->belongsTo(Section::class, 'sectionID');
    }

    public function strand() {
        return $this->belongsTo(Strand::class, 'strandID');
    }

    public function schoolYear() {
        return $this->belongsTo(SchoolYear::class, 'schoolYearID');
    }
}

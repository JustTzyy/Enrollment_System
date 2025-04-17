<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectAssignment extends Model
{
    protected $table = 'subject_assignments';

    use HasFactory, SoftDeletes;
    protected $fillable = ['code', 'semester', 'time', 'gradeLevel', 'subjectID', 'strandID'];

    public function strand()
    {
        return $this->belongsTo(Strand::class,  'strandID');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subjectID');
    }
}

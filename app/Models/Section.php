<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'sections';

    protected $fillable = ['section', 'room', 'description', 'gradeLevel', 'semester', 'strandID'];

    public function strand()
    {
        return $this->belongsTo(Strand::class, 'strandID');
    }

    // In Section.php model
    public function schedule()
    {
        return $this->hasMany(Schedule::class, 'sectionID');
    }

    public function enrollments() {
        return $this->hasMany(Enrollment::class, 'sectionID');
    }   
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Strand extends Model
{
    use HasFactory, SoftDeletes;    

    protected $table = 'strands'; 

    protected $fillable = ['strand', 'description']; 


    public function section()
    {
        return $this->hasMany(Section::class, 'strandID');
    }

    public function subjectAssignment()
    {
        return $this->hasMany(SubjectAssignment::class, 'strandID');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'strandID');
    }
}

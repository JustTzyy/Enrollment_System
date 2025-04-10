<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    protected $fillable = ['name', 'description',];

    public function studentRequirement()
    {
        return $this->hasMany(related: StudentRequirement::class, foreignKey: 'requirementID');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'student_requirements', 'requirementID', 'userID');
    }

}

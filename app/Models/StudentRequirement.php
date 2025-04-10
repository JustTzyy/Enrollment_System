<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentRequirement extends Model
{
    protected $fillable = ['userID', 'requirementID'];

    public function user()
    {
        return $this->belongsTo(User::class, foreignKey: 'userID');
    }
    public function requirement()
    {
        return $this->belongsTo(related: Requirement::class,  foreignKey: 'requirementID');
    }
}

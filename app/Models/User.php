<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Address;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = ['firstName','middleName','lastName','age','birthday','gender','contactNumber','status','email','name','password','archive','test','roleID'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'roleID');
    }

    public function getFullNameAttribute()
    {
        return "{$this->firstName} {$this->middleName} {$this->lastName}";
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'userID', 'id');
    }

    public function history()
    {
        return $this->hasMany(History::class, 'userID', 'id');
    }
    public function guardian()
    {
        return $this->hasOne(Guardian::class, 'userID', 'id');
    }
    public function studentRequirement()
    {
        return $this->hasMany(StudentRequirement::class, 'userID', 'id');
    }

    public function teacherAssignment()
    {
        return $this->hasMany(TeacherAssignment::class, 'userID', 'id');
    }

    public function requirements()
    {
        return $this->belongsToMany(Requirement::class, 'student_requirements', 'userID', 'requirementID');
    }

}

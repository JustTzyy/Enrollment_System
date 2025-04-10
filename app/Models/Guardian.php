<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Guardian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'guardians'; // Define table name

    protected $fillable = ['firstName','middleName',
        'lastName',
        'contactNumber',
        'userID',
    ];

    // Relationship: A Guardian belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class, foreignKey: 'userID');
    }
}

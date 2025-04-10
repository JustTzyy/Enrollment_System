<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Requirement;
use App\Models\StudentRequirement;

class RequirementController extends Controller
{
    public function requirement()
{
    $users = User::where('roleID', 3)->get();

    $requirements = Requirement::all();

    return view('AdminComponents.requirement', compact('users', 'requirements'));
}
}

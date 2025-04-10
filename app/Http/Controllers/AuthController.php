<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('login', 'password');

        $user = User::where('email', $credentials['login'])
            ->orWhere('name', $credentials['login'])
            ->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);

            if ($user->roleID == 1) {
                return redirect('/Dashboard/admin');
            } elseif ($user->roleID == 2) {
                return redirect('/Dashboard/teacher');
            } elseif ($user->roleID == 3) {
                return redirect('/Dashboard/student');
            } elseif ($user->roleID == 4) {
                return redirect('/Dashboard/operator');
            } else {
                return redirect('/Authentication/login')
                    ->withErrors('Role not recognized.')
                    ->withInput();
            }
        } else {
            return redirect('/Authentication/login')
                ->with('error', 'Invalid username/email or password.')
                ->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/Authentication/login')->with('success', 'You have been logged out.');
    }

    public function adminDashboard()
    {
        $activeYear = SchoolYear::where('status', 'active')->first();
        $studentCount = User::where('roleID', 3)->count();
        $section = Section::get()->count();

        return view('AdminComponents.dashboard', compact('activeYear', 'studentCount', 'section'));


    }

    public function teacherDashboard()
    {
        return view('dashboard.teacher');
    }

    public function studentDashboard()
    {
        return view('dashboard.student');
    }

    public function operatorDashboard()
    {
        return view('dashboard.operator');
    }
}

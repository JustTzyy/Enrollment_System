<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\Section;
use Cookie;
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
                Cookie::queue('username', $user->name, 60); 

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
        $forgetCookie = \Cookie::forget('username');

        return redirect('/Authentication/login')->with('success', 'You have been logged out.')->withCookie($forgetCookie);
    }

    public function adminDashboard(Request $request)
{
    $selectedYearID = $request->input('schoolYear');

    // If a specific year is selected, get it; otherwise get the active year
    if ($selectedYearID) {
        $activeYear = SchoolYear::find($selectedYearID);
    } else {
        $activeYear = SchoolYear::where('status', 'active')->first();
    }

    $schoolYears = SchoolYear::orderByDesc('yearStart')->get();
    $studentCount = User::where('roleID', 3)->count();
    $section = Section::count();

    if ($activeYear) {
        $totalEnrolled = Enrollment::where('schoolYearID', $activeYear->id)->count();
    }

    return view('AdminComponents.dashboard', compact(
        'activeYear',
        'schoolYears',
        'studentCount',
        'section',
        'totalEnrolled' // Pass it to the view
    ));
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
        $activeYear = SchoolYear::where('status', 'active')->first();
        $studentCount = User::where('roleID', 3)->count();
        $section = Section::get()->count();

        return view('OperatorComponents.dashboard', compact('activeYear', 'studentCount', 'section'));
    }
}

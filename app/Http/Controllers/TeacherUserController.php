<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Subject;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use App\Models\Address;
use App\Models\Schedule;
use App\Models\Enrollment;

class TeacherUserController extends Controller
{

    public function class()
    {
        $user = Auth::user(); // Logged-in teacher

        $userId = Auth::id();

        $schedules = Schedule::with([
            'subjectAssignment.subject',
            'section',
            'user'
        ])
            ->where('userID', $userId)
            ->get();

        $subjects = $schedules->map(function ($schedule) {
            $section = $schedule->section;

            $students = Enrollment::with('user')
                ->where('sectionID', $section->id)
                ->get()
                ->map(function ($enrollment) {
                    return (object) [
                        'student_id' => $enrollment->user->id,
                        'name' => $enrollment->user->firstName . ' ' . $enrollment->user->lastName,
                        'year' => $enrollment->gradeLevel,
                        'email' => $enrollment->user->email ?? 'N/A',
                    ];
                });

            return (object) [
                'subject_name' => $schedule->subjectAssignment->subject->subject ?? 'N/A',
                'code' => $schedule->subjectAssignment->code ?? 'N/A',
                'status' => 'Scheduled',
                'teacher_name' => $schedule->user->firstName . ' ' . $schedule->user->lastName,
                'schedule' => $schedule->day . ', ' . \Carbon\Carbon::parse($schedule->startTime)->format('h:i A') . ' - ' . \Carbon\Carbon::parse($schedule->endTime)->format('h:i A'),
                'room' => $section->room ?? 'TBD',
                'classmates' => $students,
                'section' => $section->section ?? 'N/A',
                'strand' => $section->strand->strand ?? 'N/A',
                'year' => $section->year->schoolYear ?? 'N/A',
                'semester' => $schedule->subjectAssignment->semester ?? 'N/A',
            ];
        });

        return view('TeacherComponents.class', compact('subjects', 'user'));
    }


    public function specialists()
    {
        $user = Auth::user(); // Logged-in teacher

        // Join teacher_assignments with subjects to get subject details
        $assignedSubjects = DB::table('teacher_assignments')
            ->join('subjects', 'teacher_assignments.subjectID', '=', 'subjects.id')
            ->where('teacher_assignments.userID', $user->id)
            ->select('subjects.subject', 'subjects.type', 'subjects.description', 'teacher_assignments.code')
            ->get();
        return view('TeacherComponents.specialists', compact('user', 'assignedSubjects'));
    }

    public function core()
    {
        $user = Auth::user(); // Logged-in teacher

        return view('TeacherComponents.corecommitments', compact('user'));
    }

    public function index()
    {
        $user = Auth::user();

        Cookie::queue('username', $user->name, 60);

        $address = Address::where('userID', $user->id)->first();

        $roles = Role::all();

        return view('TeacherComponents.setting', compact('user', 'address', 'roles'));
    }

    public function updateUser(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'lastName' => 'required|string|max:255',
            'age' => 'nullable|integer',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'contactNumber' => 'nullable|string',
            'name' => 'required|string|unique:users,name,' . $user->id,
            'password' => 'nullable|min:6'
        ]);

        $user->update([
            'firstName' => $validated['firstName'],
            'middleName' => $validated['middleName'],
            'lastName' => $validated['lastName'],
            'age' => $validated['age'],
            'birthday' => $validated['birthday'],
            'gender' => $validated['gender'],
            'email' => $validated['email'],
            'contactNumber' => $validated['contactNumber'],
            'name' => $validated['name'],
            'test' => True,
            'password' => $validated['password'] ? Hash::make($validated['password']) : $user->password,
        ]);

        return back()->with('success', 'User info updated successfully!');
    }

    public function updateAddress(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'street' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'zipcode' => 'nullable|string|max:10',
        ]);

        $address = Address::firstOrCreate(
            ['userID' => $user->id],
            ['userID' => $user->id]
        );

        $address->update($validated);

        return back()->with('success', 'Address updated successfully!');
    }
}

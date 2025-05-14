<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Role;
use App\Models\Strand;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Schedule;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use App\Models\StudentRequirement;
use App\Models\Requirement;

class StudentUserController extends Controller
{



    public function credential()
    {
        $user = Auth::user();

        // Get submitted requirements for the authenticated user
        $submittedRequirements = StudentRequirement::with('requirement')
            ->where('userID', $user->id)
            ->get();

        return view('StudentComponents.credential', compact('user', 'submittedRequirements'));
    }

    public function spr()
    {
        $user = User::find(Auth::id());

        if ($user->roleID !== 3) {
            abort(403, 'Access denied.');
        }

        // Get enrollment with strand and section
        $enrollment = Enrollment::with('strand', 'section')
            ->where('userID', $user->id)
            ->latest()
            ->first();

        // Get assigned subjects through subject_assignments
        $subjects = [];

        if ($enrollment && $enrollment->strandID) {
            $subjects = DB::table('subject_assignments')
                ->join('subjects', 'subject_assignments.subjectID', '=', 'subjects.id')
                ->where('subject_assignments.strandID', $enrollment->strandID)
                ->select(
                    'subject_assignments.code',
                    'subject_assignments.gradeLevel',
                    'subject_assignments.semester',
                    'subjects.subject',
                    'subjects.description'
                )
                ->orderBy('subject_assignments.gradeLevel')
                ->orderBy('subject_assignments.semester')
                ->get()
                ->groupBy(['gradeLevel', 'semester']);

            return view('StudentComponents.spr', compact('user', 'enrollment', 'subjects'));
        }
        // If not enrolled, show an error message in the view
        $error = 'You are not enrolled yet. Please contact the registrar or enroll to view your SPR.';
        return view('StudentComponents.spr', compact('user', 'enrollment', 'subjects', 'error'));
    }

    public function core()
    {
        $user = User::find(Auth::id());

        return view('StudentComponents.corecommitments', compact('user'));
    }

    public function class()
    {
        $user = Auth::user();
        $userID = Auth::id();

        // Get the logged-in student's sectionID
        $userSectionID = DB::table('enrollments')
            ->where('userID', $userID)
            ->value('sectionID');

       

        // Fetch subjects associated with the student's section
        $subjects = DB::table('schedules')
            ->join('subject_assignments', 'schedules.subjectAssignmentID', '=', 'subject_assignments.id')
            ->join('subjects', 'subject_assignments.subjectID', '=', 'subjects.id')
            ->leftJoin('users as teachers', 'schedules.userID', '=', 'teachers.id')  // Changed to LEFT JOIN
            ->join('sections', 'schedules.sectionID', '=', 'sections.id')
            ->select([
                'schedules.sectionID',
                'subjects.subject as subject_name',
                'subjects.id as subject_id',
                DB::raw("IFNULL(CONCAT(teachers.firstName, ' ', teachers.lastName), 'No Teacher Assigned') as teacher_name"),  // Conditional for teacher_name
                DB::raw("CONCAT(schedules.day, ' ', TIME_FORMAT(schedules.startTime, '%h:%i %p'), ' - ', TIME_FORMAT(schedules.endTime, '%h:%i %p')) as schedule"),
                'sections.room as room',
                DB::raw("'Active' as status"),
            ])
            ->whereNull('subject_assignments.deleted_at')
            // Directly filter by the student's section ID from the enrollments table
            ->where('schedules.sectionID', $userSectionID)
            ->get();

        // Fetch all classmates in the same section (including the current user)
        $classmates = DB::table('enrollments')
            ->join('users', 'enrollments.userID', '=', 'users.id')
            ->select([
                'users.id as student_id',
                DB::raw("CONCAT(users.firstName, ' ', users.lastName) as name"),
                'users.email',
                'enrollments.gradeLevel as year',
            ])
            ->where('enrollments.sectionID', $userSectionID)
            ->whereNull('users.deleted_at')
            ->get();

             // Check if the user has a section assigned (to prevent errors if no section is found)
        if (!$userSectionID) {
            return view('StudentComponents.class', [
                'subjects' => [],
                'classmates' => [],
                'sectionID' => null,
                'user' => $user,
                'error' => 'No section found for this user.',
            ]);
                }

        return view('StudentComponents.class', [
            'subjects' => $subjects,
            'classmates' => $classmates,
            'sectionID' => $userSectionID,
            'user' => $user,
        ]);
    }

    public function index()
    {
        $user = Auth::user();

        Cookie::queue('username', $user->name, 60);

        $address = Address::where('userID', $user->id)->first();

        $roles = Role::all();

        return view('StudentComponents.setting', compact('user', 'address', 'roles'));
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

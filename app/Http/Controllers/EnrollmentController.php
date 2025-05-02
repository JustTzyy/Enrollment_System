<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\User;
use App\Models\Strand;
use App\Models\Section;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEnrollmentRequest;
use Exception;
use Illuminate\Database\QueryException;

class EnrollmentController extends Controller
{
    public function enrollment(Request $request)
    {
        try {
            // Build the enrollments query
            $query = Enrollment::with(['user', 'strand', 'section', 'schoolYear']);
            if ($request->has('search')) {
                $search = $request->search;
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('firstName', 'LIKE', "%{$search}%")
                        ->orWhere('lastName', 'LIKE', "%{$search}%");
                });
            }
            $enrollments = $query->paginate(5);

            // Fetch only the users who are students
            // **Assuming** your users table has a 'role_id' column where 3 = student
            $includedStudentIDs = $enrollments->pluck('userID')->toArray();

            // Fetch students who are not enrolled in an active school year,
            // but also include students already in the current enrollment list
            $students = User::where(function ($query) use ($includedStudentIDs) {
                $query->whereDoesntHave('enrollments', function($q){
                    $q->whereHas('schoolYear', function($q){
                        $q->where('status', 'active');
                    });
                })
                ->orWhereIn('id', $includedStudentIDs);
            })->where('roleID', 3)->get();


            // Also pass strands, sections, and school years for the modal dropdowns
            $strands = Strand::all();
            $sections = Section::all();
            $schoolYears = SchoolYear::all();
            $activeSchoolYear = SchoolYear::where('status', 'active')->first();

            return view('AdminComponents.enrollment', compact(
                'enrollments',
                'students',
                'strands',
                'sections',
                'schoolYears', 'activeSchoolYear'
            ));
        } catch (Exception $e) {
            return redirect()
                ->route('AdminComponents.enrollment')
                ->with('error', 'Failed to load enrollments: ' . $e->getMessage());
        }
    }

    public function store(StoreEnrollmentRequest $request)
    {
        try {
            // Check if the student is already enrolled in the selected school year
            $existingEnrollment = Enrollment::where('userID', $request->userID)
                                            ->where('schoolYearID', $request->schoolYearID)
                                            ->first();
    
            if ($existingEnrollment) {
                return redirect()->back()->withErrors(['enrollment_error' => 'This student is already enrolled in the selected school year.']);
            }
    
            // Proceed to create the enrollment if no existing enrollment found
            Enrollment::create([
                'userID' => $request->userID,
                'gradeLevel' => $request->gradeLevel,
                'semester' => $request->semester,
                'sectionID' => $request->sectionID,
                'strandID' => $request->strandID,
                'schoolYearID' => $request->schoolYearID,
            ]);
    
            return redirect()->back()->with('success', 'Student enrolled successfully!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    
    public function update(StoreEnrollmentRequest $request, $id)
    {
        try {
            $enrollment = Enrollment::findOrFail($id);
            $enrollment->update([
                'userID' => $request->userID,
                'gradeLevel' => $request->gradeLevel,
                'sectionID' => $request->sectionID,
                'semester' => $request->semester,
                'strandID' => $request->strandID,
                'schoolYearID' => $request->schoolYearID,
            ]);

            return redirect()->back()->with('success', 'Enrollment updated successfully!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $enrollment = Enrollment::findOrFail($id);
            $enrollment->delete();

            return redirect()->back()->with('success', 'Enrollment deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete enrollment: ' . $e->getMessage());
        }
    }
    public function getSectionsByStrandAndGrade(Request $request)
{
    $strandID = $request->input('strand_id');
    $gradeLevel = $request->input('grade_level');
    $semester = $request->input('semester'); // No need to cast to int

    $sections = Section::where('strandID', $strandID)
                       ->where('gradeLevel', $gradeLevel) 
                       ->where('semester', $semester) // Grade level as string
                       ->get();

    return response()->json($sections);
}   
    }
    
    


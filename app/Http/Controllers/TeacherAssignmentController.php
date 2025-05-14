<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Exception;
use DB;

class TeacherAssignmentController extends Controller
{
    /**
     * Display the teacher assignments page
     */
    public function teacherAssignment(Request $request)
    {
        // Get all assignments with relationships
        $assignments = $this->getAssignments($request);
        
        // Get paginated teachers (5 per page)
        $users = $this->getPaginatedTeachers();
        
        // Get all teachers for dropdown
        $users2 = User::where('roleID', 2)->get();
       
        // Get all subjects
                $subjects = Subject::all();

        return view('AdminComponents.teacherassignment', compact('assignments', 'users', 'subjects', 'users2'));
    }

    /**
     * Store new teacher assignments
     */
    public function store(Request $request)
    {
        $request->validate([
            'userID' => 'required|exists:users,id',
            'subjectIDs' => 'required|array|min:1',
            'subjectIDs.*' => 'exists:subjects,id',
        ]);

        try {
            foreach ($request->subjectIDs as $subjectID) {
                $assignment = TeacherAssignment::create([
                    'subjectID' => $subjectID,
                    'userID' => $request->userID,
                ]);

                $assignment->update([
                    'code' => 'teach' . $assignment->id,
                ]);
            }

            return redirect()->back()->with('success', 'Teacher assignments created successfully!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Update teacher assignments
     */
    public function update(Request $request, $userId)
    {
        $data = $request->validate([
            'subjectIDs' => 'required|array|min:1',
            'subjectIDs.*' => 'exists:subjects,id',
        ]);

        try {
            DB::transaction(function () use ($userId, $data) {
                // Get current assignments for this teacher
                $currentAssignments = TeacherAssignment::where('userID', $userId)->get();
                $currentSubjectIDs = $currentAssignments->pluck('subjectID')->toArray();

                // Find subjects to delete and add
                $subjectsToDelete = array_diff($currentSubjectIDs, $data['subjectIDs']);
                $subjectsToAdd = array_diff($data['subjectIDs'], $currentSubjectIDs);

                // Delete removed subjects
                if (!empty($subjectsToDelete)) {
                    TeacherAssignment::where('userID', $userId)
                        ->whereIn('subjectID', $subjectsToDelete)
                        ->delete();
                }

                // Add new subjects
                foreach ($subjectsToAdd as $subjectID) {
                    $assignment = TeacherAssignment::create([
                        'userID' => $userId,
                        'subjectID' => $subjectID,
                    ]);

                    $assignment->update(['code' => 'teach' . $assignment->id]);
                }
            });

            return redirect()->back()->with('success', 'Teacher subjects updated successfully!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Delete a teacher assignment
     */
    public function destroy($id)
    {
        try {
            $assignment = TeacherAssignment::findOrFail($id);
            $assignment->delete();

            return redirect()->back()->with('success', 'Teacher assignment deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete teacher assignment: ' . $e->getMessage());
        }
    }

    /**
     * Get assignments with search functionality
     */
    private function getAssignments(Request $request)
    {
        $query = TeacherAssignment::with(['user', 'subject']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', "%{$search}%")
                    ->orWhere('id', 'LIKE', "{$search}")
                    ->orWhereHas('subject', fn($q) =>
                        $q->where('subject', 'LIKE', "%{$search}%"))
                    ->orWhereHas('user', fn($q) =>
                        $q->where('firstName', 'LIKE', "%{$search}%")
                            ->orWhere('middleName', 'LIKE', "%{$search}%")
                            ->orWhere('lastName', 'LIKE', "%{$search}%"));
            });
        }

        return $query->get();
    }

    /**
     * Get paginated teachers
     */
    private function getPaginatedTeachers()
    {
        return User::where('roleID', 2)
            ->whereIn('id', function($query) {
                $query->select('userID')->from('teacher_assignments')->distinct();
            })
            ->paginate(5);
    }
}

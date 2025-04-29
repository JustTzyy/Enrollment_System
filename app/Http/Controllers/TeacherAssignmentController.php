<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherAssignmentRequest;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Models\User;
use App\Models\Strand;
use DB;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TeacherAssignmentController extends Controller
{

    public function teacherAssignment(Request $request)
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

        $assignments = $query->get(); 
        $users = User::where('roleID', 2)
        ->whereIn('id', function($query) {
            $query->select('userID')->from('teacher_assignments')->distinct();
        })
        ->paginate(5);

        $users2 = User::where('roleID', 2)->get();
       
                $subjects = Subject::all();

        return view(
            'AdminComponents.teacherassignment',
            compact('assignments', 'users', 'subjects', 'users2')
        );
    }





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

    public function update(Request $request, $userId)
    {
        // Validate that we have at least one subjectID, and that each exists
        $data = $request->validate([
            'subjectIDs' => 'required|array|min:1',
            'subjectIDs.*' => 'exists:subjects,id',
        ]);

        try {
            DB::transaction(function () use ($userId, $data) {
                // 1) Remove all existing assignments for this teacher
                TeacherAssignment::where('userID', $userId)->delete();

                // 2) Re‐create for each submitted subjectID
                foreach ($data['subjectIDs'] as $subjectID) {
                    $assignment = TeacherAssignment::create([
                        'userID' => $userId,
                        'subjectID' => $subjectID,
                    ]);
                    // regenerate a unique code if needed
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
}

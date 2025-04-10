<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherAssignmentRequest;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Models\User;
use App\Models\Strand;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TeacherAssignmentController extends Controller
{
    public function teacherAssignment(Request $request)
    {
        try {
            $query = TeacherAssignment::with(['user', 'subject']);

            if ($request->has('search')) {
                $search = $request->search;
        
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('time', 'LIKE', "%{$search}%")
                      ->orWhere('id', 'LIKE', "%{$search}%")
                      ->orWhereHas('subject', function ($q) use ($search) {
                          $q->where('subject', 'LIKE', "%{$search}%");
                      })
                      ->orWhereHas('user', function ($q) use ($search) {
                          $q->where('firstName', 'LIKE', "%{$search}%")
                            ->orWhere('middleName', 'LIKE', "%{$search}%")
                            ->orWhere('lastName', 'LIKE', "%{$search}%");
                      });
                });
            }

            $assignments = $query->paginate(5);
            $users = User::where('roleID', 2)->get();
            $subjects = Subject::all();

            return view('AdminComponents.teacherassignment', compact('assignments', 'users', 'subjects'));
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', 'Failed to load teacher assignments: ' . $e->getMessage());
        }
    }

    public function store(StoreTeacherAssignmentRequest $request)
    {
        try {
            TeacherAssignment::create([
                'title' => $request->title,
                'time' => $request->time,
                'subjectID' => $request->subjectID,
                'userID' => $request->userID,
            ]);

            return redirect()->back()->with('success', 'Teacher assignment created successfully!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function update(StoreTeacherAssignmentRequest $request, $id)
    {
        try {
            $assignment = TeacherAssignment::findOrFail($id);
            $assignment->update([
                'title' => $request->title,
                'time' => $request->time,
                'subjectID' => $request->subjectID,
                'userID' => $request->userID,
            ]);

            return redirect()->back()->with('success', 'Teacher assignment updated successfully!');
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

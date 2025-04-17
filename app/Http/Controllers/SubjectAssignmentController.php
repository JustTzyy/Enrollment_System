<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectAssignmentRequest;
use App\Models\Strand;
use App\Models\Subject;
use App\Models\SubjectAssignment;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SubjectAssignmentController extends Controller
{
    public function subjectAssignment(Request $request)
    {
        try {
            $query = SubjectAssignment::with(['strand', 'subject']);

            if ($request->has('search')) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('code', 'LIKE', "%{$search}%")
                      ->orWhere('id', 'LIKE', "%{$search}%")
                      ->orWhere('gradeLevel', 'LIKE', "%{$search}%")
                      ->orWhere('semester', 'LIKE', "%{$search}%")
                      ->orWhereHas('subject', function ($q) use ($search) {
                          $q->where('subject', 'LIKE', "%{$search}%");
                      })
                      ->orWhereHas('strand', function ($q) use ($search) {
                          $q->where('strand', 'LIKE', "%{$search}%");
                      });
                });
            }

            $assignments = $query->paginate(5);
            $strands = Strand::all();
            $subjects = Subject::all();

            return view('AdminComponents.subjectassignment', compact('assignments', 'strands', 'subjects'));
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Failed to load subject assignments: ' . $e->getMessage());
        }
    }

    public function store(StoreSubjectAssignmentRequest $request)
    {
        try {
            $assignment = SubjectAssignment::create([
                'subjectID'   => $request->subjectID,
                'strandID'    => $request->strandID,
                'time'   => $request->timeLimit,
                'gradeLevel'  => $request->gradeLevel,
                'semester'    => $request->semester,
            ]);

            $assignment->update([
                'code' => 'subj' . $assignment->id,
            ]);

            return redirect()->back()->with('success', 'Subject assignment created successfully!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function update(StoreSubjectAssignmentRequest $request, $id)
    {
        try {
            $assignment = SubjectAssignment::findOrFail($id);
            $assignment->update([
                'subjectID'   => $request->subjectID,
                'strandID'    => $request->strandID,
                'timeLimit'   => $request->timeLimit,
                'gradeLevel'  => $request->gradeLevel,
                'semester'    => $request->semester,
            ]);

            return redirect()->back()->with('success', 'Subject assignment updated successfully!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $assignment = SubjectAssignment::findOrFail($id);
            $assignment->delete();

            return redirect()->back()->with('success', 'Subject assignment deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete subject assignment: ' . $e->getMessage());
        }
    }
}

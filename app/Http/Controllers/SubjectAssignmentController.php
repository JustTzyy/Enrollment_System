<?php

namespace App\Http\Controllers;

use App\Models\Strand;
use App\Models\Subject;
use App\Models\SubjectAssignment;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Exception;

class SubjectAssignmentController extends Controller
{
    public function subjectAssignment(Request $request)
    {
        $query = SubjectAssignment::with(['strand', 'subject']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', "%{$search}%")
                  ->orWhere('id', 'LIKE', "{$search}")
                  ->orWhere('gradeLevel', 'LIKE', "%{$search}%")
                  ->orWhere('semester', 'LIKE', "%{$search}%")
                  ->orWhereHas('subject', fn($q) =>
                      $q->where('subject', 'LIKE', "%{$search}%"))
                  ->orWhereHas('strand', fn($q) =>
                      $q->where('strand', 'LIKE', "%{$search}%"));
            });
        }

        $assignments = $query->get(); 
        $strands = Strand::whereIn('id', function($query) {
            $query->select('strandID')->from('subject_assignments')->distinct();
        })
        ->paginate(5); 
         $subjects = Subject::all();

        return view('AdminComponents.subjectassignment', compact('assignments', 'strands', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'strandID' => 'required|exists:strands,id',
            'subjectIDs' => 'required|array|min:1',
            'subjectIDs.*' => 'exists:subjects,id',
            'gradeLevel' => 'required|string',
            'semester' => 'required|string',
            'timeLimit' => 'nullable|string',
        ]);

        try {
            foreach ($request->subjectIDs as $subjectID) {
                $assignment = SubjectAssignment::create([
                    'strandID' => $request->strandID,
                    'subjectID' => $subjectID,
                    'gradeLevel' => $request->gradeLevel,
                    'semester' => $request->semester,
                    'timeLimit' => $request->timeLimit,
                ]);

                $assignment->update([
                    'code' => 'subj' . $assignment->id,
                ]);
            }

            return redirect()->back()->with('success', 'Subject assignments created successfully!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $strandID)
    {
        $request->validate([
            'subjectIDs' => 'required|array|min:1',
            'subjectIDs.*' => 'exists:subjects,id',
            'gradeLevel' => 'required|string',
            'semester' => 'required|string',
            'timeLimit' => 'nullable|string',
        ]);

        try {
            \DB::transaction(function () use ($strandID, $request) {
                // Get current assignments for this strand
                $currentAssignments = SubjectAssignment::where('strandID', $strandID)
                    ->where('gradeLevel', $request->gradeLevel)
                    ->where('semester', $request->semester)
                    ->get();

                // Get IDs of current assignments
                $currentSubjectIDs = $currentAssignments->pluck('subjectID')->toArray();
                
                // Find subjects to delete (in current but not in new list)
                $subjectsToDelete = array_diff($currentSubjectIDs, $request->subjectIDs);
                
                // Find subjects to add (in new list but not in current)
                $subjectsToAdd = array_diff($request->subjectIDs, $currentSubjectIDs);

                // Delete removed subjects
                if (!empty($subjectsToDelete)) {
                    SubjectAssignment::where('strandID', $strandID)
                        ->where('gradeLevel', $request->gradeLevel)
                        ->where('semester', $request->semester)
                        ->whereIn('subjectID', $subjectsToDelete)
                    ->delete();
                }

                // Add new subjects
                foreach ($subjectsToAdd as $subjectID) {
                    $assignment = SubjectAssignment::create([
                        'strandID' => $strandID,
                        'subjectID' => $subjectID,
                        'gradeLevel' => $request->gradeLevel,
                        'semester' => $request->semester,
                        'timeLimit' => $request->timeLimit,
                    ]);

                    $assignment->update(['code' => 'subj' . $assignment->id]);
                }

                // Update timeLimit for existing assignments
                if (!empty($request->timeLimit)) {
                    SubjectAssignment::where('strandID', $strandID)
                        ->where('gradeLevel', $request->gradeLevel)
                        ->where('semester', $request->semester)
                        ->whereIn('subjectID', array_intersect($currentSubjectIDs, $request->subjectIDs))
                        ->update(['timeLimit' => $request->timeLimit]);
                }
            });

            return redirect()->back()->with('success', 'Subject assignments updated successfully!');
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

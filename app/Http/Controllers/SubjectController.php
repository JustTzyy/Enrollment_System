<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectRequest;
use App\Models\Subject;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function subject(Request $request)
    {
        try {
            $query = Subject::query(); 
    
            if ($request->has('search')) {
                $search = $request->search;
                $query->where('subject', 'LIKE', "%{$search}%")
                      ->orWhere('id', 'LIKE', "{$search}")
                      ->orWhere('type', 'LIKE', "%{$search}");
            }

    
            $subjects = $query->paginate(5);
    
            return view('AdminComponents.subject', compact('subjects'));
        } catch (Exception $e) {
            dd($e->getMessage()); 

            return redirect()->route('subjects')->with('error', 'Failed to load subjects: ' . $e->getMessage());
        }
    }
    
    public function store(StoreSubjectRequest $request)
    {
        try {
            Subject::create([
                'subject' => $request->subject,
                'description' => $request->description,
                'type' => $request->type, 
            ]);

            return redirect()->back()->with('success', 'Subject added successfully!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function update(StoreSubjectRequest $request, $id)
    {
        try {
            $subject = Subject::findOrFail($id);
            $subject->update([
                'subject' => $request->subject,
                'description' => $request->description,
                'type' => $request->type, 
            ]);

            return redirect()->back()->with('success', 'Subject updated successfully!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $subject = Subject::findOrFail($id);
            $subject->delete();

            return redirect()->back()->with('success', 'Subject deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete subject: ' . $e->getMessage());
        }
    }
}


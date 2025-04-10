<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSectionRequest;
use App\Models\Section;
use App\Models\Strand;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function section(Request $request)
    {
        try {
            $query = Section::with('strand'); 

            if ($request->has('search')) {
                $search = $request->search;
                $query->where('section', 'LIKE', "%{$search}%")
                    ->orWhere('id', 'LIKE', "{$search}");
            }

            $sections = $query->paginate(5);

            $strands = Strand::all(); 
            

            return view('AdminComponents.section', compact('sections', 'strands'));
        } catch (Exception $e) {
            dd($e->getMessage());

            return redirect()->route('sections')->with('error', 'Failed to load sections: ' . $e->getMessage());
        }
    }

    public function store(StoreSectionRequest $request)
    {
        
        try {
            Section::create([
                'section' => $request->section,
                'room' => $request->room,
                'description' => $request->description,
                'strandID' => $request->strandID,
            ]);

            return redirect()->back()->with('success', 'Section added successfully!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function update(StoreSectionRequest $request, $id)
    {
        try {
            $section = Section::findOrFail($id);
            $section->update([
                'section' => $request->section,
                'room' => $request->room,
                'description' => $request->description,
                'strandID' => $request->strandID,
            ]);

            return redirect()->back()->with('success', 'Section updated successfully!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $section = Section::findOrFail($id);
            $section->delete();

            return redirect()->back()->with('success', 'Section deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete section: ' . $e->getMessage());
        }
    }
}

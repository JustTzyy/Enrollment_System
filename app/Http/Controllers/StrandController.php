<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStrandRequest;
use App\Http\Requests\UpdateStrandRequest;
use App\Models\Strand;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class StrandController extends Controller
{
    public function strand(Request $request)
    {
        try {
            $query = Strand::query(); 
    
            if ($request->has('search')) {
                $search = $request->search;
                $query->where('strand', 'LIKE', "%{$search}%")
                      ->orWhere('id', 'LIKE', "{$search}");
            }
    
            $strands = $query->paginate(5);
    
            return view('AdminComponents.strand', compact('strands'));
        } catch (Exception $e) {
            dd($e->getMessage()); 

            return redirect()->route('strands')->with('error', 'Failed to load strands: ' . $e->getMessage());
        }
    }
    
    

    public function store(StoreStrandRequest $request)
    {
        try {
            Strand::create([
                'strand' => $request->strand,
                'description' => $request->description,
            ]);

            return redirect()->back()->with('success', 'Strand added successfully!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function update(StoreStrandRequest $request, $id)
    {
        try {
            $strand = Strand::findOrFail($id);
            $strand->update([
                'strand' => $request->strand,
                'description' => $request->description,
            ]);

            return redirect()->back()->with('success', 'Strand updated successfully!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $strand = Strand::findOrFail($id);
            $strand->delete();

            return redirect()->back()->with('success', 'Strand deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete strand: ' . $e->getMessage());
        }
    }
}

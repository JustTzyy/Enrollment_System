<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSchoolYearRequest;
use App\Models\SchoolYear;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SchoolYearController extends Controller
{
    public function schoolYear(Request $request)
    {
        try {
            $query = SchoolYear::query(); 

            if ($request->has('search')) {
                $search = $request->search;
                $query->where('yearStart', 'LIKE', "%{$search}%")
                      ->orWhere('yearEnd', 'LIKE', "%{$search}%")
                      ->orWhere('status', 'LIKE', "%{$search}%");
            }

            $schoolYears = $query->paginate(5);

            return view('AdminComponents.schoolyear', compact('schoolYears'));
        } catch (Exception $e) {
            dd($e->getMessage()); 

            return redirect()->route('schoolyear')->with('error', 'Failed to load school years: ' . $e->getMessage());
        }
    }

    public function store(StoreSchoolYearRequest $request)
    {
        try {
            SchoolYear::create([
                'yearStart' => $request->yearStart,
                'yearEnd' => $request->yearEnd,
                'status' => 'Inactive', 
            ]);

            return redirect()->back()->with('success', 'School Year added successfully!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function update(StoreSchoolYearRequest $request, $id)
    {
        try {
            $schoolYear = SchoolYear::findOrFail($id);
            $schoolYear->update([
                'yearStart' => $request->yearStart,
                'yearEnd' => $request->yearEnd,
            ]);

            return redirect()->back()->with('success', 'School Year updated successfully!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $schoolYear = SchoolYear::findOrFail($id);
            $schoolYear->delete();

            return redirect()->back()->with('success', 'School Year deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete school year: ' . $e->getMessage());
        }
    }

    public function setActive($id)
{
    try {
        SchoolYear::query()->update(['status' => 'Inactive']);

        $schoolYear = SchoolYear::findOrFail($id);
        $schoolYear->update(['status' => 'Active']);

        return redirect()->back()->with('success', 'School year set to active successfully.');
    } catch (Exception $e) {
        return redirect()->back()->with('error', 'Failed to update school year: ' . $e->getMessage());
    }
}

}

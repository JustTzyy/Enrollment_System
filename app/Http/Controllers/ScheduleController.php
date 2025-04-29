<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSectionRequest;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\Strand;
use App\Models\SubjectAssignment;
use App\Models\TeacherAssignment;
use DB;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function section(Request $request)
    {
        try {
            // Build the query for sections with an optional search filter
            $query = Section::with('strand')
                ->when($request->has('search'), function ($q, Request $request) {
                    $search = $request->search;
                    $q->where('section', 'LIKE', "{$search}%")
                        ->orWhere('id', 'LIKE', "{$search}");
                });

            // Get sections with pagination
            $sections = $query->paginate(perPage: 5);

            // Get all available strands
            $strands = Strand::all();

            // Get schedules with related subject assignments and users (teachers)
            $schedules = Schedule::with(['subjectAssignment', 'user'])->get();

            // Get all teacher‐to‐subject assignments (only eager‐load the user)
            $availableTeachers = TeacherAssignment::with('user')->get();

            // dd($availableTeachers);

            return view('AdminComponents.schedule', compact('sections', 'strands', 'schedules', 'availableTeachers'));

        } catch (Exception $e) {
            // Dump error for debugging
            dd($e->getMessage());

            return redirect()
                ->route('sections')
                ->with('error', 'Failed to load sections: ' . $e->getMessage());
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



    public function update(Request $request, $sectionId)
    {
        $section = Section::with('schedule')->findOrFail($sectionId);

        $validator = Validator::make($request->all(), [
            'startTime' => 'required|array',
            'startTime.*' => 'required|date_format:H:i',
            'day' => 'required|array',
            'day.*' => 'required|string',
            'teacher' => 'nullable|array',
            'teacher.*' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        foreach ($section->schedule as $index => $schedule) {
            if (!isset($request->startTime[$index], $request->day[$index])) {
                continue;
            }

            try {
                $startTime = Carbon::createFromFormat('H:i', $request->startTime[$index])->format('H:i');
            } catch (Exception $e) {
                continue;
            }

            $day = $request->day[$index];
            $teacherId = $request->teacher[$index] ?? null;

            // ✅ Check for conflict only if a teacher is selected
            if ($teacherId) {
                $conflict = Schedule::where('userID', $teacherId)
                    ->where('day', $day)
                    ->where('startTime', $startTime)
                    ->where('id', '!=', $schedule->id) // don't conflict with itself
                    ->exists();

                if ($conflict) {
                    return redirect()->back()
                        ->withErrors(['conflict' => "Schedule conflict: Teacher already has a class on $day at $startTime."])
                        ->withInput();
                }
            }

            // ✅ Save updates
            $schedule->startTime = $startTime;
            $schedule->day = $day;
            $schedule->userID = $teacherId;
            $schedule->save();
        }

        return redirect()->back()->with('success', 'Schedules updated successfully!');
    }



    public function generate($id)
    {
        DB::beginTransaction();

        try {
            $section = Section::findOrFail($id);

            // Check if this section is already scheduled
            if (Schedule::where('sectionID', $section->id)->exists()) {

                return back()->with('error', 'This section is already scheduled.');
            }

            $subjects = SubjectAssignment::where([
                'strandID' => $section->strandID,
                'gradeLevel' => $section->gradeLevel,
                'semester' => $section->semester,
            ])->get();

            $cursor = Carbon::createFromFormat('H:i', '07:00');

            foreach ($subjects as $sa) {
                $ta = TeacherAssignment::where('subjectID', $sa->subjectID)->first();

                // Check conflict only if there's a teacher
                if ($ta) {
                    $conflict = Schedule::where('userID', $ta->userID)
                        ->where('day', 'MWF')
                        ->where('startTime', $cursor->format('H:i'))
                        ->exists();

                    if ($conflict) {
                        \Log::info("Conflict for Teacher {$ta->userID} at {$cursor->format('H:i')}, scheduling with null teacher.");
                        $ta = null; // Nullify teacher so the subject still gets scheduled
                    }
                } else {
                    \Log::warning("No teacher assigned for SubjectAssignment #{$sa->id}");
                }

                $end = $cursor->copy()->addHour();

                Schedule::create([
                    'userID' => $ta?->userID, // nullable
                    'sectionID' => $section->id,
                    'subjectAssignmentID' => $sa->id,
                    'day' => 'MWF',
                    'startTime' => $cursor->format('H:i'),
                    'endTime' => $end->format('H:i'),
                ]);

                \Log::info("Scheduled SA#{$sa->id} at {$cursor->format('H:i')}–{$end->format('H:i')}");

                $cursor->addHour();
            }

            DB::commit();
            return back()->with('success', 'Schedule generated successfully!');

        } catch (Exception $e) {
            DB::rollback();
            \Log::error("Schedule generation failed: " . $e->getMessage());
            \Log::error($e->getTraceAsString()); // Add this to see where exactly it failed
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateTeacher(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        $newUserID = $request->input('userID');

        // Check if this teacher is allowed to teach this subject
        $isAssigned = TeacherAssignment::where([
            'userID' => $newUserID,
            'subjectID' => $schedule->subjectAssignment->subjectID
        ])->exists();

        if (!$isAssigned) {
            return back()->withErrors(['error' => 'This teacher is not assigned to this subject.']);
        }

        // Check for schedule conflict
        $conflict = Schedule::where('userID', $newUserID)
            ->where('day', $schedule->day)
            ->where(function ($q) use ($schedule) {
                $q->whereBetween('startTime', [$schedule->startTime, $schedule->endTime])
                    ->orWhereBetween('endTime', [$schedule->startTime, $schedule->endTime]);
            })
            ->where('id', '!=', $schedule->id)
            ->exists();

        if ($conflict) {
            return back()->withErrors(['error' => 'Schedule conflict for this teacher.']);
        }

        // Update the teacher
        $schedule->update([
            'userID' => $newUserID
        ]);

        return back()->with('success', 'Schedule updated successfully.');
    }




}

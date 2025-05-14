<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\StoreSectionRequest;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\Strand;
use App\Models\SubjectAssignment;
use App\Models\TeacherAssignment;
use DB;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Enrollment;
use App\Models\Requirement;
use App\Models\SchoolYear;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Models\History;
use Illuminate\Support\Facades\Cookie;
use App\Models\Address;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Models\StudentRequirement;
use App\Models\Guardian;


class OperatorUserController extends Controller
{

  public function section(Request $request)
  {
    try {
      // Build the query for sections with an optional search filter
      $query = Section::with('strand')
        ->when($request->has('search'), function ($q) use ($request) {
          $search = $request->search;
          $q->where(function ($query) use ($search) {
            $query->where('section', 'LIKE', "%{$search}%")
              ->orWhere('room', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%")
              ->orWhereHas('strand', function ($q) use ($search) {
                $q->where('strand', 'LIKE', "%{$search}%");
              });
          });
        });

      // Get sections with pagination
      $sections = $query->paginate(perPage: 5);

      // Get all available strands
      $strands = Strand::all();

      // Get schedules with related subject assignments and users (teachers)
      $schedules = Schedule::with(['subjectAssignment', 'user'])->get();

      // Get all teacher‐to‐subject assignments (only eager‐load the user)
      $availableTeachers = TeacherAssignment::with('user')->get();

      return view('OperatorComponents.schedule', compact('sections', 'strands', 'schedules', 'availableTeachers'));

    } catch (Exception $e) {
      // Dump error for debugging
      dd($e->getMessage());

      return redirect()
        ->route('sections')
        ->with('error', 'Failed to load sections: ' . $e->getMessage());
    }
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



  //Enrollment
  public function enrollment(Request $request)
  {
    try {
      // Build the enrollments query
      $query = Enrollment::with(['user', 'strand', 'section', 'schoolYear']);
      if ($request->has('search')) {
        $search = $request->search;
        $query->whereHas('user', function ($q) use ($search) {
          $q->where('firstName', 'LIKE', "%{$search}%")
            ->orWhere('lastName', 'LIKE', "%{$search}%");
        });
      }
      $enrollments = $query->paginate(5);

      // Fetch only the users who are students
      // **Assuming** your users table has a 'role_id' column where 3 = student
      $includedStudentIDs = $enrollments->pluck('userID')->toArray();

      // Fetch students who are not enrolled in an active school year,
      // but also include students already in the current enrollment list
      $students = User::where(function ($query) use ($includedStudentIDs) {
        $query->whereDoesntHave('enrollments', function ($q) {
          $q->whereHas('schoolYear', function ($q) {
            $q->where('status', 'active');
          });
        })
          ->orWhereIn('id', $includedStudentIDs);
      })->where('roleID', 3)->get();


      // Also pass strands, sections, and school years for the modal dropdowns
      $strands = Strand::all();
      $sections = Section::all();
      $schoolYears = SchoolYear::all();
      $activeSchoolYear = SchoolYear::where('status', 'active')->first();

      return view('OperatorComponents.enrollment', compact(
        'enrollments',
        'students',
        'strands',
        'sections',
        'schoolYears',
        'activeSchoolYear'
      ));
    } catch (Exception $e) {
      return redirect()
        ->route('OperatorComponents.enrollment')
        ->with('error', 'Failed to load enrollments: ' . $e->getMessage());
    }
  }

  public function store(StoreEnrollmentRequest $request)
  {
    try {
      // Check if the student is already enrolled in the selected school year
      $existingEnrollment = Enrollment::where('userID', $request->userID)
        ->where('schoolYearID', $request->schoolYearID)
        ->first();

      if ($existingEnrollment) {
        return redirect()->back()->withErrors(['enrollment_error' => 'This student is already enrolled in the selected school year.']);
      }

      // Proceed to create the enrollment if no existing enrollment found
      Enrollment::create([
        'userID' => $request->userID,
        'gradeLevel' => $request->gradeLevel,
        'semester' => $request->semester,
        'sectionID' => $request->sectionID,
        'strandID' => $request->strandID,
        'schoolYearID' => $request->schoolYearID,
      ]);

      return redirect()->back()->with('success', 'Student enrolled successfully!');
    } catch (QueryException $e) {
      return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
    } catch (Exception $e) {
      return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
  }

  public function update(StoreEnrollmentRequest $request, $id)
  {
    try {
      $enrollment = Enrollment::findOrFail($id);
      $enrollment->update([
        'userID' => $request->userID,
        'gradeLevel' => $request->gradeLevel,
        'sectionID' => $request->sectionID,
        'semester' => $request->semester,
        'strandID' => $request->strandID,
        'schoolYearID' => $request->schoolYearID,
      ]);

      return redirect()->back()->with('success', 'Enrollment updated successfully!');
    } catch (QueryException $e) {
      return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
    } catch (Exception $e) {
      return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
  }

  public function destroy($id)
  {
    try {
      $enrollment = Enrollment::findOrFail($id);
      $enrollment->delete();

      return redirect()->back()->with('success', 'Enrollment deleted successfully!');
    } catch (Exception $e) {
      return redirect()->back()->with('error', 'Failed to delete enrollment: ' . $e->getMessage());
    }
  }
  public function getSectionsByStrandAndGrade(Request $request)
  {
    $strandID = $request->input('strand_id');
    $gradeLevel = $request->input('grade_level');
    $semester = $request->input('semester'); // No need to cast to int

    $sections = Section::where('strandID', $strandID)
      ->where('gradeLevel', $gradeLevel)
      ->where('semester', $semester) // Grade level as string
      ->get();

    return response()->json($sections);
  }

  // Archives
  public function arstudent(Request $request)
  {
    try {
      $query = User::with('address', 'guardian', 'studentRequirement')->where('roleID', 3)->where('archive', true);

      if ($request->has('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
          $q->where('id', 'LIKE', "%{$search}%")
            ->orWhere('firstName', 'LIKE', "%{$search}%")
            ->orWhere('lastName', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "{$search}")
            ->orWhereHas('address', function ($q) use ($search) {
              $q->where('street', 'LIKE', "%{$search}%")
                ->orWhere('city', 'LIKE', "%{$search}%")
                ->orWhere('province', 'LIKE', "%{$search}%");

            });
        });
      }

      $users = $query->paginate(5);

      return view('OperatorComponents.archive', compact('users'));


    } catch (Exception $e) {
      return redirect()->back()->with('error', 'Failed to load users: ' . $e->getMessage());
    }
  }

  public function archive($id)
  {
    $user = User::findOrFail($id);
    $user->archive = !$user->archive;
    $user->save();

    return redirect()->back()->with('success', 'User archive status updated.');
  }

  // History
  public function studenthistory(Request $request)
  {
    try {
      $query = History::with([
        'user' => function ($q) {
          $q->withTrashed();
        }
      ])
        ->withTrashed()
        ->whereHas('user', function ($q) {
          $q->withTrashed()
            ->where('roleID', 3);
        })
        ->orderBy('created_at', 'desc');

      if ($request->has('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
          $q->whereHas('user', function ($q) use ($search) {
            $q->withTrashed()
              ->where('firstName', 'LIKE', "%{$search}%")
              ->orWhere('lastName', 'LIKE', "%{$search}%")
              ->orWhere('id', 'LIKE', "{$search}");
          });

          $q->orWhere('id', 'LIKE', "%{$search}%")
            ->orWhere('status', 'LIKE', "{$search}");

        });
      }

      if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
      }

      if ($request->filled('status')) {
        $query->where('status', $request->status);
      }

      // Ensure filters persist across pages
      $histories = $query->paginate(5)->appends($request->query());

      return view('OperatorComponents.studenthistory', compact('histories'));

    } catch (\Exception $e) {
      return redirect()->back()->with('error', 'Failed to load history: ' . $e->getMessage());
    }
  }

  public function enrollmenthistory(Request $request)
  {
    try {
      // Start building the query
      $query = Enrollment::with(['user', 'section', 'schoolYear', 'strand'])
        ->orderBy('created_at', 'desc');

      if ($request->has('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
          $q->whereHas('user', function ($q) use ($search) {
            $q->where('users.firstName', 'LIKE', "%{$search}%")
              ->orWhere('users.lastName', 'LIKE', "%{$search}%")
              ->orWhere('users.id', 'LIKE', "%{$search}%"); // users.id
          })->orWhere('enrollments.id', 'LIKE', "%{$search}%");
        });
      }

      $enrollments = $query->paginate(10); // or ->get() if you don't want pagination


      // Apply filters for gradeLevel, semester, and schoolYear if provided
      if ($request->filled('gradeLevel')) {
        $query->where('gradeLevel', $request->gradeLevel);
      }
      if ($request->filled('semester')) {
        $query->where('semester', $request->semester);
      }
      if ($request->filled('schoolYear')) {
        $query->where('school_year_id', $request->schoolYear);
      }

      // Apply pagination before calling get()
      $histories = $query->paginate(5)->appends($request->query());

      // Get school years for the filter dropdown
      $schoolYears = SchoolYear::orderByDesc('yearStart')->get();

      // Return the view with the data
      return view('OperatorComponents.enrollmenthistory', compact('histories', 'schoolYears'));

    } catch (\Exception $e) {
      return redirect()->back()->with('error', 'Failed to load enrollment history: ' . $e->getMessage());
    }
  }





  public function student(Request $request)
    {
        try {
            $query = User::with('address', 'guardian', 'studentRequirement')->where('roleID', 3)->where('archive', false);

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('id', 'LIKE', "%{$search}%")
                        ->orWhere('firstName', 'LIKE', "%{$search}%")
                        ->orWhere('lastName', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "{$search}")
                        ->orWhereHas('address', function ($q) use ($search) {
                            $q->where('street', 'LIKE', "%{$search}%")
                                ->orWhere('city', 'LIKE', "%{$search}%")
                                ->orWhere('province', 'LIKE', "%{$search}%");

                        });
                });
            }

            $users = $query->paginate(5);

            return view('OperatorComponents.student', compact('users'));


        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to load users: ' . $e->getMessage());
        }
    }
   // Store new user with address
   public function studentstore(StoreUserRequest $request)
   {

       try {

           $fullName = $request->firstName . ($request->middleName ?? '') . $request->lastName;
           $Name = $request->firstName . ' ' . ($request->middleName ?? '') . ' ' . $request->lastName;

           //add User
           $user = User::create([
               'firstName' => $request->firstName,
               'middleName' => $request->middleName,
               'lastName' => $request->lastName,
               'name' => $fullName,
               'email' => $request->email,
               'birthday' => $request->birthday,
               'age' => $request->age,
               'gender' => $request->gender,
               'contactNumber' => $request->contactNumber,
               'password' => Hash::make($request->firstName . '123'),
               'roleID' => $request->roleID,
               'status' => $request->status,
           ]);


           //Add Adress
           Address::create([
               'userID' => $user->id,
               'street' => $request->street,
               'city' => $request->city,
               'province' => $request->province,
               'zipcode' => $request->zipcode,
           ]);

           // Add Guardian if roleID == 3
           if ($request->roleID == '3') {
               Guardian::create([
                   'firstName' => $request->gfirstName,
                   'middleName' => $request->gmiddleName,
                   'lastName' => $request->glastName,
                   'contactNumber' => $request->gcontactNumber,
                   'userID' => $user->id,
               ]);

               $selectedRequirements = [];

               $fields = ['psd', 'ef', 'gc', 'f137', 'nso', 'gm', 'ncae'];
               foreach ($fields as $field) {
                   if ($request->filled($field)) {
                       $selectedRequirements[] = (int) $request->$field;
                   }
               }

               if (!empty($selectedRequirements)) {
                   foreach ($selectedRequirements as $requirementID) {

                       StudentRequirement::create([
                           'userID' => $user->id,
                           'requirementID' => $requirementID,
                       ]);
                   }
               }
           }

           // Add History
           History::create([
               'status' => 'Added',
               'userID' => $user->id,

           ]);

           return redirect()->back()->with('success', 'User added successfully!');

       } catch (QueryException $e) {
           return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
       } catch (Exception $e) {
           return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
       }
   }

   // Update user and address
   public function studentupdate(StoreUserRequest $request, $id)
   {

       try {
           $user = User::findOrFail($id);


           //Update user
           $user->update([
               'firstName' => $request->firstName,
               'middleName' => $request->middleName,
               'lastName' => $request->lastName,
               'name' => trim($request->firstName . ($request->middleName ?? '') . $request->lastName),
               'email' => $request->email,
               'birthday' => $request->birthday,
               'age' => $request->age,
               'gender' => $request->gender,
               'contactNumber' => $request->contactNumber,

           ]);


           //Update address
           $address = Address::where('userID', $id)->first();
           if ($address) {
               $address->update([
                   'street' => $request->street,
                   'city' => $request->city,
                   'province' => $request->province,
                   'zipcode' => $request->zipcode,
               ]);
           } else {
               Address::create([
                   'userID' => $user->id,
                   'street' => $request->street,
                   'city' => $request->city,
                   'province' => $request->province,
                   'zipcode' => $request->zipcode,
               ]);
           }

           //Update Guardian
           if ($user->roleID == 3) {
               $guardian = Guardian::where('userID', $id)->first();
               if ($guardian) {
                   $guardian->update([
                       'firstName' => $request->gfirstName,
                       'middleName' => $request->gmiddleName,
                       'lastName' => $request->glastName,
                       'contactNumber' => $request->gcontactNumber,
                   ]);
               } else {
                   Guardian::create([
                       'firstName' => $request->gfirstName,
                       'middleName' => $request->gmiddleName,
                       'lastName' => $request->glastName,
                       'contactNumber' => $request->gcontactNumber,
                       'userID' => $user->id,
                   ]);
               }

               // Update or create the selected requirements
               // Get selected requirements from request
               $selectedRequirements = $request->input('requirements', []);

               // Fetch existing user requirements
               $existingRequirements = StudentRequirement::where('userID', $user->id)->pluck('requirementID')->toArray();

               // Find requirements to delete (unchecked by user)
               $requirementsToDelete = array_diff($existingRequirements, $selectedRequirements);
               StudentRequirement::where('userID', $user->id)
                   ->whereIn('requirementID', $requirementsToDelete)
                   ->delete();

               // Find requirements to add (newly checked by user)
               $requirementsToAdd = array_diff($selectedRequirements, $existingRequirements);
               foreach ($requirementsToAdd as $requirementID) {
                   StudentRequirement::create([
                       'userID' => $user->id,
                       'requirementID' => $requirementID,
                   ]);
               }

           }

           //Add history
           History::create([
               'status' => 'Updated',
               'userID' => $user->id,

           ]);

           return redirect()->back()->with('success', 'User and address updated successfully!');
       } catch (QueryException $e) {
           return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
       } catch (Exception $e) {
           return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
       }
   }

   // Delete user and address
   public function studentdestroy($id)
   {
       try {
           $user = User::findOrFail($id);

           // Soft delete User
           $user->delete();

           // Add history
           History::create([
               'status' => 'Deleted',
               'userID' => $user->id,
           ]);

           return redirect()->back()->with('success', 'User deleted successfully!');
       } catch (QueryException $e) {
           return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
       } catch (Exception $e) {
           return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
       }
   }

   public function studentarchive($id)
   {
       $user = User::findOrFail($id);
       $user->archive = true;
       $user->save();

       return redirect()->back()->with('success', 'User archived successfully.');
   }

  // Requirements
  public function requirement(Request $request)
  {
    try {
      $query = User::with('address', 'guardian', 'studentRequirement')->where('roleID', 3)->where('archive', false);

      if ($request->has('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
          $q->where('id', 'LIKE', "{$search}")
            ->orWhere('firstName', 'LIKE', "%{$search}%")
            ->orWhere('lastName', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "{$search}");

        });
      }

      $users = $query->paginate(5);


      $requirements = Requirement::all();

      return view('OperatorComponents.requirement', compact('users', 'requirements'));
    } catch (Exception $e) {
      return redirect()->back()->with('error', 'Failed to load users: ' . $e->getMessage());
    }
  }
  // Settings
  public function index()
  {
      $user = Auth::user();

      Cookie::queue('username', $user->name, 60); 

      $address = Address::where('userID', $user->id)->first();

      $roles = Role::all();

      return view('OperatorComponents.setting', compact('user', 'address', 'roles'));
  }

  public function updateUser(Request $request)
  {
      $user = Auth::user();

      $validated = $request->validate([
          'firstName' => 'required|string|max:255',
          'middleName' => 'nullable|string|max:255',
          'lastName' => 'required|string|max:255',
          'age' => 'nullable|integer',
          'birthday' => 'nullable|date',
          'gender' => 'nullable|string',
          'email' => 'required|email|unique:users,email,' . $user->id,
          'contactNumber' => 'nullable|string',
          'name' => 'required|string|unique:users,name,' . $user->id,
          'password' => 'nullable|min:6'
      ]);

      $user->update([
          'firstName' => $validated['firstName'],
          'middleName' => $validated['middleName'],
          'lastName' => $validated['lastName'],
          'age' => $validated['age'],
          'birthday' => $validated['birthday'],
          'gender' => $validated['gender'],
          'email' => $validated['email'],
          'contactNumber' => $validated['contactNumber'],
          'name' => $validated['name'],
          'test' => True,
          'password' => $validated['password'] ? Hash::make($validated['password']) : $user->password,
      ]);

      return back()->with('success', 'User info updated successfully!');
  }

  public function updateAddress(Request $request)
  {
      $user = Auth::user();

      $validated = $request->validate([
          'street' => 'nullable|string|max:255',
          'city' => 'nullable|string|max:255',
          'province' => 'nullable|string|max:255',
          'zipcode' => 'nullable|string|max:10',
      ]);

      $address = Address::firstOrCreate(
          ['userID' => $user->id],
          ['userID' => $user->id]
      );

      $address->update($validated);

      return back()->with('success', 'Address updated successfully!');
  }
}
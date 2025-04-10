<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreUserRequest;
use App\Models\StudentRequirement;
use App\Models\User;
use App\Models\Address;
use App\Models\Guardian;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Exception;
use App\Models\History;
use Illuminate\Http\Request;
use Log;
use Schema;



class UserController extends Controller
{
    public function admin(Request $request)
    {
        try {
            $query = User::with('address')->where('roleID', 1);

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
            return view('AdminComponents.admin', compact('users'));

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to load users: ' . $e->getMessage());
        }
    }

    public function operator(Request $request)
    {
        try {
            $query = User::with('address')->where('roleID', 4);

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
            return view('AdminComponents.operator', compact('users'));

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to load users: ' . $e->getMessage());
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

            return view('AdminComponents.student', compact('users'));


        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to load users: ' . $e->getMessage());
        }
    }

    public function teacher(Request $request)
    {
        try {
            $query = User::with('address')->where('roleID', 2);

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
            return view('AdminComponents.teacher', compact('users'));

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to load users: ' . $e->getMessage());
        }
    }



    // Store new user with address
    public function store(StoreUserRequest $request)
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
    public function update(StoreUserRequest $request, $id)
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
                'status' => $request->status,

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
    public function destroy($id)
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

    public function archive($id)
    {
        $user = User::findOrFail($id);
        $user->archive = true;
        $user->save();

        return redirect()->back()->with('success', 'User archived successfully.');
    }
}

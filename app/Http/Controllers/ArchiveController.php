<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function student(Request $request)
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

            return view('AdminComponents.archive', compact('users'));


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

}

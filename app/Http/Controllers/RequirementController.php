<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Models\Requirement;
use App\Models\StudentRequirement;

class RequirementController extends Controller
{
    public function requirement(Request $request)
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


    $requirements = Requirement::all();

    return view('AdminComponents.requirement', compact('users', 'requirements'));
} catch (Exception $e) {
    return redirect()->back()->with('error', 'Failed to load users: ' . $e->getMessage());
}
}
}

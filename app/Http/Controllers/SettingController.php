<?php

namespace App\Http\Controllers;

use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\Address;
use App\Models\Role;

class SettingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        Cookie::queue('username', $user->name, 60); 

        $address = Address::where('userID', $user->id)->first();

        $roles = Role::all();

        return view('AdminComponents.setting', compact('user', 'address', 'roles'));
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

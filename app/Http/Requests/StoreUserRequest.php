<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route(param: 'id'); // Get the user ID from the route

        return [
            

            //User
            'firstName' => 'required',
            'lastName' => 'required',
            'middleName' => 'nullable',
            'email' =>  "required|email|unique:users,email,$userId,id",
            'birthday' => 'required|date',
            'age' => 'required|integer',
            'gender' => 'required',
            'contactNumber' => 'required',
            'status' => 'nullable',

            //Address
            'street' => 'required',
            'city' => 'required',
            'province' => 'required',
            'zipcode' => 'required',

            // Guardian
            'gfirstName' => ['nullable', 'string', 'max:255'], 
            'gmiddleName' => ['nullable', 'string', 'max:255'],
            'glastName' => ['nullable', 'string', 'max:255'],
            'gcontactNumber' => ['nullable', 'string', 'min:10', 'max:15'], 

            //Requirement
            'psd' => 'nullable|integer',
            'ef' => 'nullable|integer',
            'gc' => 'nullable|integer',
            'f137' => 'nullable|integer',
            'nso' => 'nullable|integer',
            'gm' => 'nullable|integer',
            'ncae' => 'nullable|integer',
        ];
    }
}

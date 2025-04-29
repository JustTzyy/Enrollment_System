<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnrollmentRequest extends FormRequest
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
        return [
            'gradeLevel'   => 'required|string',
            'userID'       => 'required|exists:users,id',
            'schoolYearID' => 'required|exists:school_years,id',
            'sectionID'    => 'required|exists:sections,id',
            'strandID'     => 'required|exists:strands,id',
        ];
    }
}

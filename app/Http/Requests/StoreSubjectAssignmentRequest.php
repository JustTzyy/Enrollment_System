<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectAssignmentRequest extends FormRequest
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
        $AId = $this->route(param: 'id'); 

        return [
            'subjectID' => 'required|exists:subjects,id',
            'strandID' => 'required|exists:strands,id',
            'gradeLevel' =>'required',
            'timeLimit' => 'required',
            'semester' => 'required'
        ];
    }
}

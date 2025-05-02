<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSectionRequest extends FormRequest
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
        $sectionId = $this->route(param: 'id'); 
        

        return [
            'section' => "required|string|unique:strands,strand,$sectionId,id",
            'room' => "required",
            'description' => 'nullable',
            'strandID' => 'required',
            'semester' => 'required',
            'gradeLevel' => 'required'
            
        ];
        
        
    }
}

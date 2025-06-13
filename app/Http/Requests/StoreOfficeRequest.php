<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOfficeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $officeId = $this->id ?? null;

        return [
            'office_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z\s\.\-]+$/',
                'unique:offices,office_name,' . $officeId,
            ],
            'office_location' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z\s\.\-]+$/',
            ],
            'swado' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z\s\.\-]+$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'office_name.required' => 'The office name is required.',
            'office_name.string' => 'The office name must be a valid string.',
            'office_name.max' => 'The office name may not be greater than 255 characters.',
            'office_name.unique' => 'This office name is already taken.',

            'office_location.required' => 'The office location is required.',
            'office_location.string' => 'The office location must be a valid string.',
            'office_location.max' => 'The office location may not be greater than 255 characters.',

            'swado.required' => 'The SWADO field is required.',
            'swado.string' => 'The SWADO must be a valid string.',
            'swado.max' => 'The SWADO may not be greater than 255 characters.',

            'office_name.regex' => 'The office name may only contain letters, spaces, and dots.',
            'office_location.regex' => 'The office location may only contain letters, spaces, and dots.',
            'swado.regex' => 'The SWADO may only contain letters, spaces, and dots.',

        ];
    }
}

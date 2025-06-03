<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SDORequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
          return [
            'firstname'       => ['required', 'string', 'max:100', 'regex:/^[A-Za-z\- ]+$/'],
            'middlename'      => ['nullable', 'string', 'max:100', 'regex:/^[A-Za-z\- ]+$/'],
            'lastname'        => ['required', 'string', 'max:100', 'regex:/^[A-Za-z\- ]+$/'],
            'extension_name'  => ['nullable', 'string', 'max:20', 'regex:/^[A-Za-z\-\. ]+$/'],
            'position'        => ['required', 'string', 'max:100'],
            'designation'     => ['string', 'max:100'],
            'station'         => ['required', 'string', 'max:100'],
            'status' => ['required', 'string', Rule::in(['Active', 'Inactive'])],
        ];
    }

    public function messages(): array
    {
        return [
            'firstname.required'      => 'The firstname is required.',
            'firstname.string'        => 'The firstname must be a valid string.',
            'firstname.max'           => 'The firstname may not be greater than 100 characters.',

            'middlename.string'       => 'The middlename must be a valid string.',
            'middlename.max'          => 'The middlename may not be greater than 100 characters.',

            'lastname.required'       => 'The lastname is required.',
            'lastname.string'         => 'The lastname must be a valid string.',
            'lastname.max'            => 'The lastname may not be greater than 100 characters.',

            'extension_name.string'   => 'The extension name must be a valid string.',
            'extension_name.max'      => 'The extension name may not be greater than 20 characters.',

            'position.required'       => 'The position is required.',
            'position.string'         => 'The position must be a valid string.',
            'position.max'            => 'The position may not be greater than 100 characters.',
            
            'designation.string'         => 'The position must be a valid string.',
            'designation.max'            => 'The position may not be greater than 100 characters.',

            'station.required'        => 'The station is required.',
            'station.string'          => 'The station must be a valid string.',
            'station.max'             => 'The station may not be greater than 100 characters.',

            'firstname.regex'       => 'The firstname must only contain letters, dashes, and spaces.',
            'middlename.regex'      => 'The middlename must only contain letters, dashes, and spaces.',
            'lastname.regex'        => 'The lastname must only contain letters, dashes, and spaces.',
            'extension_name.regex'  => 'The extension name must only contain letters, dashes, periods, and spaces.',

            'status.in' => 'The status must be either Active or Inactive.',


        ];
    }
}

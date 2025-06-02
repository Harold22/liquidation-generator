<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SDORequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstname'       => ['required', 'string', 'max:100'],
            'middlename'      => ['nullable', 'string', 'max:100'],
            'lastname'        => ['required', 'string', 'max:100'],
            'extension_name'  => ['nullable', 'string', 'max:20'],
            'position'        => ['required', 'string', 'max:100'],
            'station'         => ['required', 'string', 'max:100'],
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

            'station.required'        => 'The station is required.',
            'station.string'          => 'The station must be a valid string.',
            'station.max'             => 'The station may not be greater than 100 characters.',
        ];
    }
}

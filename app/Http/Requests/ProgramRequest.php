<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ProgramRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'program_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z0-9.\-\/ ]+$/'
            ],
            'program_abbreviation' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Za-z0-9.\-\/ ]+$/'
            ],
            'origin_office' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z0-9.\-\/ ]+$/'
            ],
            'status' => 'required|string|in:Active,Inactive',
        ];
    }

}

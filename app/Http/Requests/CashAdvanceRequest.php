<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CashAdvanceRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'special_disbursing_officer' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-zÑñ\s\-.]+$/',
                Rule::unique('cash_advances')
                    ->ignore($this->id)
                    ->where(function ($query) {
                        $query->where('cash_advance_amount', $this->cash_advance_amount)
                            ->where('cash_advance_date', $this->cash_advance_date);
                    }),
            ],
            'position' => 'required|string|max:255|regex:/^[A-Za-z0-9\-\.\/\s]+$/',
            'station' => 'required|string|max:255|regex:/^[A-Za-z0-9\-\.\/\s]+$/',
            'check_number' => 'required|string|max:255|regex:/^[A-Za-z0-9\-\.\/\s]+$/',
            'cash_advance_amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:75000000', 
            ],

            'cash_advance_date' => 'required|date|date_format:Y-m-d|before_or_equal:today',
            'dv_number' => 'required|string|max:255|regex:/^[A-Za-z0-9\-\.\/]+$/',
            'ors_burs_number' => 'required|string|max:255|regex:/^[A-Za-z0-9\-\.\/]+$/',
            'responsibility_code' => 'required|string|max:255|regex:/^[A-Za-z0-9\-\.\/]+$/',
            'uacs_code' => 'required|string|max:255|regex:/^[A-Za-z0-9\-\.\/]+$/',
            'status' => 'required|string|in:Liquidated,Unliquidated|regex:/^[A-Za-z0-9\-\.\/]+$/',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'special_disbursing_officer.required' => 'The Special Disbursing Officer field is required.',
            'special_disbursing_officer.regex' => 'The Special Disbursing Officer field must only contain letters,numbers, spaces, hyphens, and periods.',
            'special_disbursing_officer.string' => 'The Special Disbursing Officer must be a string.',
            'special_disbursing_officer.max' => 'The Special Disbursing Officer must not exceed 255 characters.',
            'special_disbursing_officer.unique' => 'This Special Disbursing Officer already exists with the same Cash Advance Amount and Date.',

            'position.required' => 'The Position field is required.',
            'position.string' => 'The Position must be a string.',
            'position.max' => 'The Position must not exceed 255 characters.',
            'position.regex' => 'The Position field must only contain letters,numbers, spaces, hyphens, and periods.',

            'station.required' => 'The Station field is required.',
            'station.string' => 'The Station must be a string.',
            'station.max' => 'The Station must not exceed 255 characters.',
            'station.regex' => 'The Station field must only contain letters,numbers, spaces, hyphens, and periods.',

            'check_number.required' => 'The Check Number field is required.',
            'check_number.string' => 'The Check Number must be a string.',
            'check_number.max' => 'The Check Number must not exceed 255 characters.',
            'check_number.regex' => 'The Check Number field must only contain letters,numbers, spaces, hyphens, and periods.',

            'cash_advance_amount.required' => 'The Cash Advance Amount field is required.',
            'cash_advance_amount.numeric' => 'The Cash Advance Amount must be a valid number.',
            'cash_advance_amount.min' => 'The Cash Advance Amount must be at least ₱0.01.',
            'cash_advance_amount.max' => 'The Cash Advance Amount must not exceed ₱75,000,000.',

            'cash_advance_date.required' => 'The Cash Advance Date field is required.',
            'cash_advance_date.date' => 'The Cash Advance Date must be a valid date.',
            'cash_advance_date.date_format' => 'The Cash Advance Date must be in the format YYYY-MM-DD.',
            'cash_advance_date.before_or_equal' => 'The Cash Advance Date must be today or a date in the past.',

            'dv_number.required' => 'The DV Number field is required.',
            'dv_number.string' => 'The DV Number must be a string.',
            'dv_number.max' => 'The DV Number must not exceed 255 characters.',
            'dv_number.regex' => 'The DV Number field must only contain letters,numbers, spaces, hyphens, and periods.',

            'ors_burs_number.required' => 'The ORS/BURS Number field is required.',
            'ors_burs_number.string' => 'The ORS/BURS Number must be a string.',
            'ors_burs_number.max' => 'The ORS/BURS Number must not exceed 255 characters.',
            'ors_burs_number.regex' => 'The ORS/BURS Number field must only contain letters,numbers, spaces, hyphens, and periods.',

            'responsibility_code.required' => 'The Responsibility Code field is required.',
            'responsibility_code.string' => 'The Responsibility Code must be a string.',
            'responsibility_code.max' => 'The Responsibility Code must not exceed 255 characters.',

            'uacs_code.required' => 'The UACS Code field is required.',
            'uacs_code.string' => 'The UACS Code must be a string.',
            'uacs_code.max' => 'The UACS Code must not exceed 255 characters.',

            'status.required' => 'The Status field is required.',
            'status.string' => 'The Status must be a string.',
            'status.in' => 'The Status must be either Liquidated or Unliquidated.',
        ];
    }
}   
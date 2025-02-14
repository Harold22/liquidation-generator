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
        return true; // Adjust this if you have authorization logic
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
                Rule::unique('cash_advances')
                    ->ignore($this->id)
                    ->where(function ($query) {
                        $query->where('cash_advance_amount', $this->cash_advance_amount)
                            ->where('cash_advance_date', $this->cash_advance_date);
                    }),
            ],
            'position' => 'required|string|max:255',
            'station' => 'required|string|max:255',
            'cash_advance_amount' => 'required|numeric|min:0.01',
            'cash_advance_date' => 'required|date|date_format:Y-m-d|before_or_equal:today',
            'dv_number' => 'required|string|max:255',
            'ors_burs_number' => 'required|string|max:255',
            'responsibility_code' => 'required|string|max:255',
            'uacs_code' => 'required|string|max:255',
            'status' => 'required|string|in:Liquidated,Unliquidated',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'special_disbursing_officer.required' => 'Special Disbursing Officer is required!',
            'cash_advance_amount.required' => 'Cash Advance Amount is required!',
            'cash_advance_amount.numeric' => 'Cash Advance Amount must be a valid number!',
            'cash_advance_date.required' => 'Cash Advance Date is required!',
            'special_disbursing_officer.unique' => 'Special Disbursing officer already exist',
        ];
    }
}

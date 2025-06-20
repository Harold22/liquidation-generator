<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CashAdvanceUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cash_advance_id' => 'required|ulid|exists:cash_advances,id',
            'allocations' => 'required|array|min:1',
            'allocations.*.id' => 'nullable|ulid|exists:cash_advance_allocations,id',
            'allocations.*.cash_advance_id' => 'nullable|ulid|exists:cash_advances,id',
            'allocations.*.office_id' => 'required|ulid|exists:offices,id',
            'allocations.*.amount' => 'required|numeric|min:0.01',
            'allocations.*.status' => 'nullable|in:liquidated,unliquidated',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function (Validator $validator) {
            $officeIds = [];

            foreach ($this->input('allocations', []) as $index => $allocation) {
                $officeId = $allocation['office_id'] ?? null;

                if ($officeId) {
                    if (in_array($officeId, $officeIds)) {
                        $validator->errors()->add("allocations.$index.office_id", 'This office is already assigned in this cash advance.');
                    } else {
                        $officeIds[] = $officeId;
                    }
                }
            }
        });
    }
}

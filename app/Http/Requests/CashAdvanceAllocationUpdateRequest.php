<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CashAdvanceAllocationUpdateRequest extends FormRequest
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

    public function messages()
    {
        return [
            'cash_advance_id.required' => 'The cash advance is required.',
            'cash_advance_id.ulid' => 'The cash advance ID must be a valid ULID.',
            'cash_advance_id.exists' => 'The selected cash advance does not exist.',

            'allocations.required' => 'At least one allocation is required.',
            'allocations.array' => 'Allocations must be an array.',
            'allocations.min' => 'At least one allocation entry is required.',

            'allocations.*.id.ulid' => 'Each allocation ID must be a valid ULID.',
            'allocations.*.id.exists' => 'One or more allocation IDs are invalid.',

            'allocations.*.cash_advance_id.ulid' => 'Each allocation must reference a valid cash advance ULID.',
            'allocations.*.cash_advance_id.exists' => 'One or more referenced cash advances do not exist.',

            'allocations.*.office_id.required' => 'Each allocation must have an office selected.',
            'allocations.*.office_id.ulid' => 'Each office ID must be a valid ULID.',
            'allocations.*.office_id.exists' => 'One or more office IDs are invalid.',

            'allocations.*.amount.required' => 'Each allocation must have an amount.',
            'allocations.*.amount.numeric' => 'Each allocation amount must be a number.',
            'allocations.*.amount.min' => 'Allocation amounts must be at least 0.01.',

            'allocations.*.status.in' => 'Status must be either "liquidated" or "unliquidated".',
        ];
    }

}

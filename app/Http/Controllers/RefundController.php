<?php

namespace App\Http\Controllers;

use App\Models\Refund;
use Illuminate\Http\Request;
use App\Services\RefundService;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class RefundController extends Controller
{
    protected $refundService;

    public function __construct(RefundService $refundService)
    {
        $this->refundService = $refundService;
    }

    public function store(Request $request)
    {   
        if ($request->input('refund_id')) {
            return $this->update($request);
        }

        try {
            $validated = $this->validateRefund($request);

            $this->refundService->create($validated);

            return redirect()->back()->with('success', 'Refund processed successfully.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    public function update(Request $request)
    {
        try {
            $validated = $this->validateRefund($request, true);

            $this->refundService->update($validated['refund_id'], $validated);

            return redirect()->back()->with('success', 'Refund updated successfully.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function validateRefund(Request $request, $isUpdate = false)
    {
        $rules = [
            'amount_refunded' => 'required|numeric|min:1|max:1000000000',
            'date_refunded' => 'required|date',
            'official_receipt' => 'required|string',
        ];

        if ($isUpdate) {
            $rules['refund_id'] = 'required|exists:refunds,id';
        } else {
            $rules['cash_advance_id'] = [
                'required',
                'exists:cash_advances,id',
                Rule::unique('refunds', 'cash_advance_id')->whereNull('deleted_at'), 
            ];
        }

        return $request->validate($rules);
    }

    
    public function show($id)
    {
        $refund = refund::where('cash_advance_id', $id)->get();

        return response()->json($refund);
    }
    
    public function destroy($refund_id)
    {
        $this->refundService->delete($refund_id);
    
        return redirect()->back()->with('success', 'Refund deleted successfully.');
    }

   

}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\CashAdvanceAllocationUpdateRequest;
use App\Models\CashAdvanceAllocation;
use App\Services\CashAdvanceAllocationService;
use Illuminate\Http\Request;


class CashAdvanceAllocationController extends Controller
{
    protected $allocationService;

    public function __construct(CashAdvanceAllocationService $allocationService)
    {
        $this->allocationService = $allocationService;
    }

    public function update(CashAdvanceAllocationUpdateRequest $request)
    {
        $validated = $request->validated();
        $updatedCashAdvance = $this->allocationService->storeOrUpdate($validated);

        return redirect()
            ->back()
            ->with('success', 'Cash advance allocations have been updated successfully.');
    }

    public function getOfficesByCashAdvance($cashAdvanceId)
    {
        $offices = $this->allocationService->getAllLocationsByCashAdvance($cashAdvanceId);
        return response()->json($offices);
    }

    public function getAllLocationByOffice(Request $request, string $office_id)
    {
        $allocations = $this->allocationService->getByOfficeId($request, $office_id);
        return response()->json($allocations);
    }

    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:cash_advance_allocations,id',
            'status' => 'required|in:liquidated,unliquidated',
        ]);

        $this->allocationService->updateStatus($validated['id'], $validated['status']);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function getAllocationBySDO(string $office_id)
    {
        $allocations = $this->allocationService->getAllocationByOfficeNoPagination( $office_id);
        return response()->json($allocations);
    }

    public function getDetails($id)
    {
        $details = $this->allocationService->getDetailsById($id);

        if (!$details) {
            return response()->json(['message' => 'Allocation not found.'], 404);
        }

        return response()->json($details);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\CashAdvanceAllocationUpdateRequest;
use App\Services\CashAdvanceAllocationService;
use Illuminate\Http\Request;

class CashAdvanceAllocationController extends Controller
{
    public function update(CashAdvanceAllocationUpdateRequest $request, CashAdvanceAllocationService $service)
    {
        $validated = $request->validated();
        $updatedCashAdvance = $service->storeOrUpdate($validated);

        return redirect()
            ->back()
            ->with('success', 'Cash advance allocations have been updated successfully.');
    }

    public function getOfficesByCashAdvance($cashAdvanceId, CashAdvanceAllocationService $service)
    {
        $offices = $service->getAllLocationsByCashAdvance($cashAdvanceId);

        return response()->json($offices);

    }
}

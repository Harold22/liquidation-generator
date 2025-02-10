<?php

namespace App\Http\Controllers;

use App\Models\CashAdvance;
use App\Services\CashAdvanceService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CashAdvanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CashAdvanceController extends Controller
{
    protected $cashAdvanceService;

    public function __construct(CashAdvanceService $cashAdvanceService)
    {
        $this->cashAdvanceService = $cashAdvanceService;
    }
    /**
     * Display a listing of the resource.
     */ 

    public function store(CashAdvanceRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $this->cashAdvanceService->createCashAdvance($request->validated());
            });

            return redirect()->back()->with('message', 'Added Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error! Please try again.');
        }
    }

    public function update(CashAdvanceRequest $request)
    {   
        try {
            DB::transaction(function () use ($request) {
                $id = $request->input('id');
                $this->cashAdvanceService->updateCashAdvance($id, $request->validated());
            });

            return redirect()->back()->with('message', 'Updated Successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }

    // Method to delete a cash advance

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:cash_advances,id',
        ]);

        try {
            $deleted = $this->cashAdvanceService->deleteCashAdvance($validated['id']);

            if ($deleted) {
                return redirect()->back()->with('success', 'Cash Advance Deleted Successfully');
            }

            return back()->with('error', 'Cash Advance Not Found');
        } catch (\Exception $e) {
            // Handle any exceptions that occur
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }


    public function show()
    {   
        $cash_advances = CashAdvance::orderBy('cash_advance_date', 'DESC')->paginate(5);
    
        return response()->json($cash_advances->toArray());
    }

    public function showSdo()
    {

        $sdo_list = CashAdvance::where('status', 'unliquidated')
            ->select('id', 'special_disbursing_officer', 'cash_advance_amount', 'cash_advance_date') 
            ->get();

        return response()->json($sdo_list);
    }

    public function getDetails($id)
    {
        $details = CashAdvance::where('id', $id)
            ->get();
        return response()->json($details);
    }

    
}

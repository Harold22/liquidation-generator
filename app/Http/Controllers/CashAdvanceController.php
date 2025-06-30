<?php

namespace App\Http\Controllers;

use App\Models\CashAdvance;
use App\Services\CashAdvanceService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CashAdvanceRequest;
use App\Http\Requests\CashAdvanceUpdateRequest;
use Illuminate\Http\Request;

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

            return redirect()->back()->with('success', 'Added Successfully');

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

            return redirect()->back()->with('success', 'Updated Successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }


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
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }
    

    public function index(Request $request)
    {
        $cashAdvances = $this->cashAdvanceService->getAllCashAdvance($request);
        return response()->json($cashAdvances);
    }


    
}

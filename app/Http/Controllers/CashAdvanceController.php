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
    

   public function index(Request $request)
    {
        $perPage = $request->input('perPage', 5);
        $sortBy = $request->input('sortBy', 'cash_advance_date');
        $sortOrder = $request->input('sortOrder', 'ASC');
        $filterBy = $request->input('filterBy');

        $query = CashAdvance::with('sdo');

        if (auth()->user()->getRoleNames()->first() === 'User') {
            $query->where('status', 'Unliquidated');
        } else {
            if ($filterBy === 'Liquidated') {
                $query->where('status', 'Liquidated');
            } elseif ($filterBy === 'Unliquidated') {
                $query->where('status', 'Unliquidated');
            }
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $terms = explode(' ', $search);

            $query->where(function ($q) use ($terms) {
                foreach ($terms as $term) {
                    $q->where(function ($subQuery) use ($term) {
                        $subQuery
                            ->orWhereHas('sdo', function ($sdoQuery) use ($term) {
                                $sdoQuery->where('firstname', 'LIKE', "%{$term}%")
                                        ->orWhere('middlename', 'LIKE', "%{$term}%")
                                        ->orWhere('lastname', 'LIKE', "%{$term}%");
                            })
                            ->orWhere('dv_number', 'LIKE', "%{$term}%")
                            ->orWhere('cash_advance_amount', 'LIKE', "%{$term}%");
                    });
                }
            });
        }

        $validSortColumns = ['cash_advance_amount', 'cash_advance_date'];
        if (in_array($sortBy, $validSortColumns)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $cash_advances = $query->paginate($perPage);

        foreach ($cash_advances as $item) {
            $item->special_disbursing_officer = $item->sdo
                ? trim("{$item->sdo->firstname} {$item->sdo->middlename} {$item->sdo->lastname} {$item->sdo->extension_name}")
                : null;
            $item->makeHidden('sdo');
        }

        return response()->json($cash_advances);
    }


    
}

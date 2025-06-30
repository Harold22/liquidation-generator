<?php

namespace App\Services;
use App\Models\CashAdvance;
use Illuminate\Http\Request;

class CashAdvanceService
{
    public function createCashAdvance($data)
    {
        return CashAdvance::create($data);
    }

    public function updateCashAdvance($id, array $data)
    {
        $cashAdvance = CashAdvance::find($id);
        
        if (!$cashAdvance) {
            return null; 
        }
        
        $cashAdvance->update($data);
        return $cashAdvance;
    }


    public function deleteCashAdvance($id)
    {
        $cashAdvance = CashAdvance::find($id);

        if (!$cashAdvance) {
            return null;
        }

        $cashAdvance->delete();
        return true;
    }
    
    public function getAllCashAdvance(Request $request)
    {
        $perPage = $request->input('perPage', 5);
        $sortBy = $request->input('sortBy', 'cash_advance_date');
        $sortOrder = $request->input('sortOrder', 'ASC');
        $filterBy = $request->input('filterBy');

        $query = CashAdvance::with(['sdo', 'program']);

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

            $item->program_name = $item->program->program_name ?? null;
            $item->program_abbreviation = $item->program->program_abbreviation ?? null;

            $item->makeHidden(['sdo', 'program']);
        }

        return $cash_advances;
    }

    
}

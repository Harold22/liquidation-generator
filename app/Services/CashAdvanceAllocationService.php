<?php

namespace App\Services;

use App\Models\CashAdvance;
use App\Models\CashAdvanceAllocation;
use Illuminate\Support\Facades\DB;

class CashAdvanceAllocationService
{
    public function storeOrUpdate(array $data)
    {
        return DB::transaction(function () use ($data) {
            $cashAdvance = CashAdvance::findOrFail($data['cash_advance_id']);

            $incomingIds = collect($data['allocations'])
                ->pluck('id')
                ->filter()
                ->all(); 
            $cashAdvance->allocations()
                ->whereNotIn('id', $incomingIds)
                ->delete();

            foreach ($data['allocations'] as $alloc) {
                if (!empty($alloc['id'])) {
        
                    $allocation = CashAdvanceAllocation::findOrFail($alloc['id']);
                    $allocation->update([
                        'office_id' => $alloc['office_id'],
                        'amount'    => $alloc['amount'],
                        'status'    => $alloc['status'],
                    ]);
                } else {
                    $cashAdvance->allocations()->create([
                        'office_id' => $alloc['office_id'],
                        'amount'    => $alloc['amount'],
                        'status'    => 'unliquidated',
                    ]);
                }
            }

            return $cashAdvance->load('allocations');
        });
    }

    public function getAllLocationsByCashAdvance($cashAdvanceId)
    {
         $allocations = CashAdvanceAllocation::where('cash_advance_id', $cashAdvanceId)
            ->get(['id','cash_advance_id', 'office_id', 'amount', 'status']);

        return $allocations;
    }

}

<?php

namespace App\Services;

use App\Http\Controllers\CashAdvanceController;
use App\Models\CashAdvance;
use App\Models\CashAdvanceAllocation;
use Illuminate\Http\Request;
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

    public function getByOfficeId(Request $request, string $officeId)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');

        $query = CashAdvanceAllocation::with(['cash_advance.sdo'])
            ->where('office_id', $officeId)->where('status', 'unliquidated');

        if ($search) {
            $query->whereHas('cash_advance', function ($q) use ($search) {
                $q->Where('cash_advance_amount', 'like', '%' . $search . '%')
                ->orWhere('cash_advance_date', 'like', '%' . $search . '%')
                ->orWhereHas('sdo', function ($s) use ($search) {
                    $s->where('firstname', 'like', '%' . $search . '%')
                        ->orWhere('middlename', 'like', '%' . $search . '%')
                        ->orWhere('lastname', 'like', '%' . $search . '%');
                });
            });
        }

        return $query->paginate($perPage)->through(function ($allocation) {
            $cashAdvance = $allocation->cash_advance;
            $sdo = $cashAdvance->sdo ?? null;

            return [
                'id' => $allocation->id,
                'cash_advance_id' => $allocation->cash_advance_id,
                'office_id' => $allocation->office_id,
                'amount' => $allocation->amount,
                'status' => $allocation->status,
                'created_at' => $allocation->created_at,
                'updated_at' => $allocation->updated_at,

                // Flattened cash advance
                'check_number' => $cashAdvance->check_number ?? null,
                'cash_advance_amount' => $cashAdvance->cash_advance_amount ?? null,
                'cash_advance_date' => $cashAdvance->cash_advance_date ?? null,
                'dv_number' => $cashAdvance->dv_number ?? null,
                'ors_burs_number' => $cashAdvance->ors_burs_number ?? null,
                'responsibility_code' => $cashAdvance->responsibility_code ?? null,
                'uacs_code' => $cashAdvance->uacs_code ?? null,

                // Flattened SDO
                'sdo_id' => $sdo->id ?? null,
                'sdo_name' => $sdo ? trim("{$sdo->firstname} {$sdo->middlename} {$sdo->lastname} {$sdo->extension_name}") : null,
                'sdo_position' => $sdo->position ?? null,
                'sdo_designation' => $sdo->designation ?? null,
                'sdo_station' => $sdo->station ?? null,
                'sdo_status' => $sdo->status ?? null,
            ];
        });
    }

    public function updateStatus(string $id, string $status): bool
    {
        $allocation = CashAdvanceAllocation::findOrFail($id);
        $allocation->status = $status;
        return $allocation->save();
    }

    public function getAllocationByOfficeNoPagination(string $officeId)
    {
        $allocations = CashAdvanceAllocation::with(['cash_advance.sdo'])
            ->where('office_id', $officeId)
            ->where('status', 'unliquidated')
            ->get()
            ->map(function ($allocation) {
                $cashAdvance = $allocation->cash_advance;
                $sdo = $cashAdvance->sdo ?? null;

                return [
                    'id' => $allocation->id,
                    'cash_advance_id' => $allocation->cash_advance_id,
                    'office_id' => $allocation->office_id,
                    'amount' => $allocation->amount,
                    'status' => $allocation->status,

                    // Flattened cash advance
                    'check_number' => $cashAdvance->check_number ?? null,
                    'cash_advance_amount' => $cashAdvance->cash_advance_amount ?? null,
                    'cash_advance_date' => $cashAdvance->cash_advance_date ?? null,

                    // Flattened SDO
                    'sdo_id' => $sdo->id ?? null,
                    'sdo_name' => $sdo ? trim("{$sdo->firstname} {$sdo->middlename} {$sdo->lastname} {$sdo->extension_name}") : null,
                ];
            });

        return $allocations;
    }




}

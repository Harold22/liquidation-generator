<?php

namespace App\Services;

use App\Http\Controllers\CashAdvanceController;
use App\Models\CashAdvance;
use App\Models\CashAdvanceAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
                    $allocation = CashAdvanceAllocation::with('files')->findOrFail($alloc['id']);

                    if (($alloc['status'] ?? null) === 'liquidated') {
                        $files = $allocation->files;

                        if ($files->isEmpty()) {
                            throw ValidationException::withMessages([
                                "allocations.{$alloc['id']}.status" => 'Cannot mark as liquidated: no files are attached to this allocation.',
                            ]);
                        }

                        $fileTotal = $files->sum('total_amount');
                        $expected = round($alloc['amount'], 2);

                        if (round($fileTotal, 2) !== $expected) {
                            throw ValidationException::withMessages([
                                "allocations.{$alloc['id']}.amount" => 'Disbursement total (₱' . number_format($fileTotal, 2) . ') does not match the allocation amount (₱' . number_format($expected, 2) . ').',
                            ]);
                        }
                    }

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
                'sdo_name' => $sdo ? trim("{$sdo->firstname} " . ($sdo->middlename ? strtoupper(substr($sdo->middlename, 0, 1)) . '. ' : '') . "{$sdo->lastname} {$sdo->extension_name}") : null,
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
                    'sdo_name' => $sdo ? trim("{$sdo->firstname} " . ($sdo->middlename ? strtoupper(substr($sdo->middlename, 0, 1)) . '. ' : '') . "{$sdo->lastname} {$sdo->extension_name}") : null,

                ];
            });

        return $allocations;
    }


    public function getDetailsById($id): ?array
    {
        $allocation = CashAdvanceAllocation::with([
            'cash_advance.sdo',
            'cash_advance.program',
            'office'
        ])->where('id', $id)->first();

        if (!$allocation) {
            return null;
        }

        $cashAdvance = optional($allocation->cash_advance);
        $program = optional($cashAdvance->program);
        $office = optional($allocation->office);
        $sdo = optional($cashAdvance->sdo);

        return [
            'id' => $allocation->id,
            'cash_advance_id' => $allocation->cash_advance_id,
            'office_id' => $allocation->office_id,
            'amount' => $allocation->amount,
            'status' => $allocation->status,

            // Cash Advance Details
            'check_number' => $cashAdvance->check_number,
            'cash_advance_amount' => $cashAdvance->cash_advance_amount,
            'cash_advance_date' => $cashAdvance->cash_advance_date,
            'dv_number' => $cashAdvance->dv_number,
            'ors_burs_number' => $cashAdvance->ors_burs_number,
            'responsibility_code' => $cashAdvance->responsibility_code,
            'uacs_code' => $cashAdvance->uacs_code,

            // Program Details
            'program_name' => $program->program_name,
            'program_abbreviation' => $program->program_abbreviation,

            // Office Details
            'office_name' => $office->office_name,
            'office_location' => $office->office_location,

            // SDO Details
            'sdo_id' => $sdo->id,
            'sdo_name' => $sdo ? trim("{$sdo->firstname} " . ($sdo->middlename ? strtoupper(substr($sdo->middlename, 0, 1)) . '. ' : '') . "{$sdo->lastname} {$sdo->extension_name}") : null,
            'sdo_position' => $sdo->position,
            'sdo_designation' => $sdo->designation,
            'sdo_station' => $sdo->station,
        ];
    }
    public function getCashAdvanceAggregates($cash_advance_id)
    {
        $allocations = CashAdvanceAllocation::with(['files', 'office'])
            ->where('cash_advance_id', $cash_advance_id)
            ->get();

        $cashAdvanceTotalAmount = 0;
        $cashAdvanceTotalBeneficiaries = 0;
        $allocationSummaries = [];

        foreach ($allocations as $allocation) {
            $allocationAmount = 0;
            $allocationBeneficiaries = 0;

            foreach ($allocation->files as $file) {
                $allocationAmount += (float) $file->total_amount;
                $allocationBeneficiaries += (int) $file->total_beneficiary;
            }

            $allocationSummaries[] = [
                'allocation_id' => $allocation->id,
                'allocation_amount' => $allocation->amount,
                'allocation_status' => $allocation->status,
                'office_id' => $allocation->office?->id,
                'office_name' => $allocation->office?->office_name,
                'total_imported_amount' => $allocationAmount,
                'total_imported_beneficiaries' => $allocationBeneficiaries,
            ];

            $cashAdvanceTotalAmount += $allocationAmount;
            $cashAdvanceTotalBeneficiaries += $allocationBeneficiaries;
        }

        return [
            'data' => [
                'cash_advance_id' => $cash_advance_id,
                'total_imported_amount' => $cashAdvanceTotalAmount,
                'total_imported_beneficiaries' => $cashAdvanceTotalBeneficiaries,
                'allocations_summary' => $allocationSummaries,
            ]
        ];
    }




}

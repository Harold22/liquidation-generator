<?php

namespace App\Http\Controllers;

use App\Services\SDOService;
use App\Http\Requests\SDORequest;
use App\Models\SDO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SDOController extends Controller
{
    protected $SDOService;

    public function __construct(SDOService $SDOService)
    {
       $this->SDOService = $SDOService;
    }
    public function store(SDORequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $this->SDOService->create($request->validated());
            });
    
            return redirect()->back()->with('success', 'Added Successfully');

        } catch (\Exception $e) {
           return redirect()->back()->with('error', 'Error! Please try again.');
        }
    }
    public function index(Request $request)
    {
        $sdoRecords = $this->SDOService->getPaginatedList($request);
        return response()->json($sdoRecords);
    }

    public function getSDOList()
    {
        $sdos = SDO::select('id', 'firstname', 'middlename', 'lastname', 'extension_name')
            ->where('status', 'Active')
            ->get();
        
        return response()->json($sdos);
    }

    public function update(SDORequest $request)
    {   
        $id = $request->id;
        $sdo = SDO::find($id);
        if (!$sdo) {
            return redirect()->back()->with('error', 'SDO not found.');
        }
        $this->SDOService->update( $id, $request->validated());

        return redirect()->back()->with('success', 'SDO updated successfully.');
    }

    public function destroy($id)
    {
        $sdo = SDO::findOrFail($id);
        $sdo->delete();

        return response()->json(['message' => 'SDO deleted successfully.']);
    }

    public function getCashAdvances($sdo_id, $year)
    {
        $sdo = SDO::with([
            'cashAdvances' => function ($query) use ($year) {
                $query->whereYear('cash_advance_date', $year)
                    ->where('status', 'Liquidated')
                    ->with(['allocations.files.file_data']);
            }
        ])->find($sdo_id);

        if ($sdo) {
            foreach ($sdo->cashAdvances as $cashAdvance) {
                $allFileData = collect();

                foreach ($cashAdvance->allocations as $allocation) {
                    foreach ($allocation->files as $file) {
                        if ($file->file_data) {
                            $allFileData = $allFileData->merge($file->file_data);
                        }
                    }
                }

                $sortedFileData = $allFileData->sortBy(function ($data) {
                    return \Carbon\Carbon::parse($data->date_time_claimed)->toDateString();
                })->values();

                $cashAdvance->sorted_file_data = $sortedFileData;
                unset($cashAdvance->allocations);
            }
        }

        return response()->json($sdo);
    }




   
}

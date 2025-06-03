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
    
            return redirect()->back()->with('message', 'Added Successfully');

        } catch (\Exception $e) {
           return redirect()->back()->with('error', 'Error! Please try again.');
        }
    }
    public function index(Request $request)
    {
        $sdoRecords = $this->SDOService->getPaginatedList($request);
        return response()->json($sdoRecords);
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
}

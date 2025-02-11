<?php

namespace App\Http\Controllers;

use App\Models\FileData;
use App\Services\FileDataService;
use Illuminate\Http\Request;

class FileDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $fileDataService;

    public function __construct(FileDataService $fileDataService)
    {
        $this->fileDataService = $fileDataService;
    }

    public function getData($fileIds)
    {
        $fileIdsArray = explode(',', $fileIds);
        $file_data = FileData::whereIn('file_id', $fileIdsArray)->orderBy('date_time_claimed', 'ASC')->get();

        if ($file_data->isEmpty()) {
            return response()->json(['message' => 'No data found for the given file IDs'], 404);
        }

        $grouped_data = $file_data->groupBy('file_id');

        return response()->json($grouped_data);
    }  

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:file_data,id',
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'extension_name' => 'nullable|string|max:50',
            'assistance_type' => 'required|string|max:255',
            'amount' => 'sometimes|numeric|min:0',
        ]);

        $fileData = FileData::findOrFail($validatedData['id']);

        $this->fileDataService->update($fileData, $validatedData);

        return redirect()->back()->with('success', 'Beneficiary updated successfully!');
    }

    public function getIndividualList(Request $request, $fileId)
    {
        $query = FileData::where('file_id', $fileId)
            ->select('id', 'firstname', 'middlename', 'lastname', 'extension_name', 'assistance_type', 'amount');

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('firstname', 'LIKE', "%{$searchTerm}%")
                ->orWhere('middlename', 'LIKE', "%{$searchTerm}%")
                ->orWhere('lastname', 'LIKE', "%{$searchTerm}%")
                ->orWhere('extension_name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('assistance_type', 'LIKE', "%{$searchTerm}%")
                ->orWhere('amount', 'LIKE', "%{$searchTerm}%");
            });
        }

        return response()->json($query->paginate(10));
    }

    

    public function destroy($id)
    {
        $beneficiary = FileData::find($id);

        if ($beneficiary) {
            $beneficiary->delete(); 

            return response()->json(['message' => 'Beneficiary Deleted successfully']);
        }

        return response()->json(['message' => 'File not found'], 404);
    }
 
}

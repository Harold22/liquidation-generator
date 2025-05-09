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

        $file_data = FileData::with('file:id,location') 
            ->whereIn('file_id', $fileIdsArray)
            ->orderBy('date_time_claimed', 'ASC')
            ->get();

        if ($file_data->isEmpty()) {
            return response()->json(['message' => 'No data found for the given file IDs'], 404);
        }

        $grouped_data = $file_data->groupBy('file_id')->map(function ($items) {
            return $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'file_id' => $item->file_id,
                    'control_number' => $item->control_number,
                    'lastname' => $item->lastname,
                    'firstname' => $item->firstname,
                    'middlename' => $item->middlename,
                    'extension_name' => $item->extension_name,
                    'birthdate' => $item->birthdate,
                    'status' => $item->status,
                    'date_time_claimed' => $item->date_time_claimed,
                    'remarks' => $item->remarks,
                    'amount' => $item->amount,
                    'assistance_type' => $item->assistance_type,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'deleted_at' => $item->deleted_at,
                    'location' => $item->file->location, 
                ];
            });
        });

        return response()->json($grouped_data);
    }


    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:file_data,id',
            'firstname' => "required|string|max:255|regex:/^[A-Za-zÑñ\s\-.]+$/",
            'middlename' => "nullable|string|max:255|regex:/^[A-Za-zÑñ\s\-.]+$/",
            'lastname' => "required|string|max:255|regex:/^[A-Za-zÑñ\s\-.]+$/",
            'extension_name' => "nullable|string|max:50|regex:/^[A-Za-zÑñ\s\-.]+$/",
            'assistance_type' => 'required|string|max:255|regex: /^[A-Za-z0-9\-\.\/\s]+$/',
            'amount' => 'sometimes|numeric|min:0|max:10000',
        ]);

        $fileData = FileData::findOrFail($validatedData['id']);

        $this->fileDataService->update($fileData, $validatedData);

        return redirect()->back()->with('success', 'Beneficiary updated successfully!');
    }

    public function getIndividualList(Request $request, $fileId)
    {
        $perPageBene = $request->input('perPageBene'); 
        $query = FileData::where('file_id', $fileId)
            ->select('id', 'firstname', 'middlename', 'lastname', 'extension_name', 'assistance_type', 'amount');

        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            $terms = explode(' ', $searchTerm); 

            $query->where(function ($q) use ($terms) {
                foreach ($terms as $term) {
                    $q->where(function ($subQuery) use ($term) {
                        $subQuery->where('firstname', 'LIKE', "%{$term}%")
                            ->orWhere('middlename', 'LIKE', "%{$term}%")
                            ->orWhere('lastname', 'LIKE', "%{$term}%")
                            ->orWhere('extension_name', 'LIKE', "%{$term}%");
                    });
                }
            });
        }

        return response()->json($query->paginate($perPageBene));
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

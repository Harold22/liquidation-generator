<?php

namespace App\Http\Controllers;

use App\Models\FileData;
use Illuminate\Http\Request;

class FileDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */

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

    public function getIndividualList($fileId)
    {
        $file_data = FileData::where('file_id', $fileId)->paginate(10);

        return response()->json($file_data->toArray());
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

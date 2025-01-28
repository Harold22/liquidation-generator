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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FileData $fileData)
    {
      
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FileData $fileData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FileData $fileData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FileData $fileData)
    {
        //
    }
}

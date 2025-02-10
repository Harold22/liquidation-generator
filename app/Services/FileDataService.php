<?php

namespace App\Services;

use App\Models\FileData;
use Illuminate\Support\Facades\DB;

class FileDataService
{
   
     /**
     * Create a new FileData record.
     *
     * @param array $data
     * @return FileData
     */
    public function create(array $data): void
    {
        FileData::insert($data);
    }

    /**
     * Update an existing file data entry.
     *
    
     */
    public function update(FileData $fileData, array $data): FileData
    {
        $fileData->update($data);
        return $fileData;
    }

    public function getByFileId(int $fileId)
    {
        return FileData::where('file_id', $fileId)->get();
    }

    /**
     * Perform a batch insert for multiple file data entries.
     *
     * @param array $data
     * @return bool
     */
    public function batchInsert(array $data): bool
    {
        return DB::table('file_data')->insert($data);
    }
}

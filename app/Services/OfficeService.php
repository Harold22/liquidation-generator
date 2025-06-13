<?php

namespace App\Services;

use App\Models\Office;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OfficeService
{
    public function create(array $data): Office
    {
        return Office::create($data);
    }

    public function update(Office $office, array $data): Office
    {
        $office->update($data);
        return $office;
    }

   public function delete(Office $office): bool
    {
        return $office->delete();
    }



}

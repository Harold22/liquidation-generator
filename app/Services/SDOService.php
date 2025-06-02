<?php

namespace App\Services;

use App\Models\SDO;

class SDOService
{   
    public function create(array $data): SDO
    {   
        dd($data);
        return SDO::create($data);
    }

    public function update(SDO $sdo, array $data)
    {
        $sdo->update($data);
        return $sdo;
    }

    public function delete(SDO $sdo): bool
    {
        return $sdo->delete();
    }

    public function find(string $id): ?SDO
    {
        return SDO::find($id);
    }

    public function all()
    {
        return SDO::all();
    }
}

<?php

namespace App\Services;

use App\Models\SDO;
use Illuminate\Http\Request;

class SDOService
{   
    public function create(array $data): SDO
    { 
        return SDO::create($data);
    }

    public function update($id, array $data)
    {
        $sdo = SDO::findOrFail($id);
        $sdo->update([
            'firstname'       => $data['firstname'],
            'middlename'      => $data['middlename'] ?? null,
            'lastname'        => $data['lastname'],
            'extension_name'  => $data['extension_name'] ?? null,
            'position'        => $data['position'],
            'designation'     => $data['designation'],
            'station'         => $data['station'],
            'status'          => $data['status'], // Active or Inactive
        ]);
        return $sdo;
    }

    public function getPaginatedList(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sortBy', 'lastname'); 
        $sortOrder = strtolower($request->input('sortOrder', 'asc')); 
        $filterByStation = $request->input('station'); 

        $query = SDO::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $terms = explode(' ', $search);

            $query->where(function ($q) use ($terms) {
                foreach ($terms as $term) {
                    $q->where(function ($subQuery) use ($term) {
                        $subQuery->where('firstname', 'LIKE', "%{$term}%")
                            ->orWhere('middlename', 'LIKE', "%{$term}%")
                            ->orWhere('lastname', 'LIKE', "%{$term}%");
                    });
                }
            });
        }

        if (!empty($filterByStation)) {
            $query->where('station', $filterByStation);
        }

        $validSortColumns = ['firstname', 'lastname', 'position', 'station'];
        if (in_array($sortBy, $validSortColumns)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        return $query->paginate($perPage);
    }

}

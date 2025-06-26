<?php

namespace App\Services;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramService
{
    public function store(array $data): Program
    {
        return Program::create($data);
    }

    public function getPaginatedList(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sortBy', 'program_name'); 
        $sortOrder = strtolower($request->input('sortOrder', 'asc')); 
        $search = $request->input('search');

        $query = Program::query();

        if (!empty($search)) {
            $terms = explode(' ', trim($search));

            $query->where(function ($q) use ($terms) {
                foreach ($terms as $term) {
                    $q->where(function ($subQuery) use ($term) {
                        $subQuery->where('program_name', 'LIKE', "%{$term}%")
                            ->orWhere('program_abbreviation', 'LIKE', "%{$term}%")
                            ->orWhere('origin_office', 'LIKE', "%{$term}%");
                    });
                }
            });
        }

        $validSortColumns = ['program_name', 'program_abbreviation', 'origin_office', 'status'];
        if (in_array($sortBy, $validSortColumns)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        return $query->paginate($perPage);
    }

    public function update(string $id, array $data): Program
    {
        $program = Program::findOrFail($id);

        $program->update($data);

        return $program;
    }

    public function getProgramsWithoutPagination()
    {
          return Program::select('id', 'program_name', 'program_abbreviation')->where('status', 'Active')->get();
    }

   
}

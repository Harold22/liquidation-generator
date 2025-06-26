<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProgramRequest;
use App\Models\Program;
use App\Services\ProgramService;
use Illuminate\Http\Request;


class ProgramController extends Controller
{
    protected ProgramService $programService;

    public function __construct(ProgramService $programService)
    {
        $this->programService = $programService;
    }

    public function store(ProgramRequest $request)
    {
        try {
            $program = $this->programService->store($request->validated());

            return redirect()->back()->with('success', "Program registered successfully.");
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Failed to register program.');
        }
    }

    public function index(Request $request)
    {
         $programs = $this->programService->getPaginatedList($request);

        return response()->json([
            'status' => 'success',
            'data' => $programs->items(),
            'current_page' => $programs->currentPage(),
            'last_page' => $programs->lastPage(),
            'total' => $programs->total(),
            'per_page' => $programs->perPage(),
        ]);
    } 

   public function update(ProgramRequest $request)
    {
       try{
            $id = $request->input('id');

            $this->programService->update($id, $request->validated());

            return redirect()->back()->with('success', 'Program updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating user. Please try again.');
        }
    }

    public function destroy($id)
    {
        try {
            $program = Program::findOrFail($id);
            $program->delete();

            return response()->json([
                'success' => true,
                'message' => 'Program Deleted successfully.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Program not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting program. Please try again.'
            ], 500);
        }
    }

     public function getPrograms()
    {
        try {
            $programs = $this->programService->getProgramsWithoutPagination();

            return response()->json([
                'data' => $programs,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch program data.',
            ], 500);
        }
    }




}

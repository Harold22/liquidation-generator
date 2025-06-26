<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOfficeRequest;
use App\Models\Office;
use App\Services\OfficeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfficeController extends Controller
{
    protected $service;

    public function __construct(OfficeService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        try {
            $perPage   = $request->input('perPage', 5);
            $sortBy    = $request->input('sortBy', 'office_name');
            $sortOrder = $request->input('sortOrder', 'ASC');
            $search    = $request->input('search');

            $query = Office::query();

            if ($search) {
                $terms = explode(' ', trim($search));
                $query->where(function ($q) use ($terms) {
                    foreach ($terms as $term) {
                        $q->orWhere('office_name', 'LIKE', "%{$term}%")
                          ->orWhere('office_location', 'LIKE', "%{$term}%")
                          ->orWhere('swado', 'LIKE', "%{$term}%");
                    }
                });
            }

            $validSortColumns = ['office_name', 'office_location', 'swado'];
            if (in_array($sortBy, $validSortColumns)) {
                $query->orderBy($sortBy, $sortOrder);
            }

            $offices = $query->paginate($perPage);

            return response()->json($offices);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch offices.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StoreOfficeRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $this->service->create($request->validated());
            });

            return redirect()->back()->with('success', 'Added Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error! Please try again.');
        }
    }

    public function update(StoreOfficeRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $office = Office::findOrFail($request->input('id'));
                $this->service->update($office, $request->validated());
            });

            return redirect()->back()->with('message', 'Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error! Please try again.');
        }
    }


    public function destroy($id)
    {
        try {
            $office = Office::findOrFail($id);
            $this->service->delete($office);

            return response()->json(['message' => 'Office deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error! Please try again.'], 500);
        }
    }

    public function getOffice()
    {
        $offices = Office::select('id', 'office_name')->orderBy('office_name')->get();
        return response()->json($offices);
    }
    public function getOfficeName($id)
    {
        $office = Office::where('id', $id)->first();

        if (!$office) {
            return response()->json(['office_name' => 'Not Found'], 404);
        }

        return response()->json(['office_name' => $office->office_name]);
    }




}

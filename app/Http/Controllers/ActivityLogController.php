<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10); 

        $query = Activity::with('causer');

        if ($search) {
            $query->whereHas('causer', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        } else {
            $query->whereDate('created_at', now()->toDateString());
        }

        $logs = $query->latest()->paginate($perPage);

        return response()->json($logs);
    }
}


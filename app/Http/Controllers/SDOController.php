<?php

namespace App\Http\Controllers;

use App\Services\SDOService;
use App\Http\Requests\SDORequest;
use Illuminate\Support\Facades\DB;

class SDOController extends Controller
{
    protected $SDOService;

    public function __construct(SDOService $SDOService)
    {
       $this->SDOService = $SDOService;
    }
    public function store(SDORequest $request)
    {
          try {
              DB::transaction(function () use ($request) {
                $this->SDOService->create($request->validated());
            });
    
            return redirect()->back()->with('message', 'Added Successfully');

        } catch (\Exception $e) {
           return redirect()->back()->with('error', 'Error! Please try again.');
        }
    }
}

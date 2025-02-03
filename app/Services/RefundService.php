<?php

namespace App\Services;

use App\Models\Refund;
use Illuminate\Support\Facades\DB;
use Exception;

class RefundService
{
    public function create($data)
    {
        return Refund::create($data);
    }

    public function update($id, $data)
    {
        $refund = Refund::findOrFail($id);
        $refund->update($data);
        return $refund;
    }

}

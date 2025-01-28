<?php

namespace App\Services;
use App\Models\CashAdvance;

class CashAdvanceService
{
    public function createCashAdvance($data)
    {
        return CashAdvance::create($data);
    }

    public function updateCashAdvance($id, array $data)
    {
        $cashAdvance = CashAdvance::find($id);
        
        if (!$cashAdvance) {
            return null; 
        }
        
        $cashAdvance->update($data);
        return $cashAdvance;
    }


    public function deleteCashAdvance($id)
    {
        $cashAdvance = CashAdvance::find($id);

        if (!$cashAdvance) {
            return null;
        }

        $cashAdvance->delete();
        return true;
    }
    
   
}

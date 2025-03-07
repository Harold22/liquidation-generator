<?php

namespace App\Http\Controllers;

use App\Models\CashAdvance;
use App\Models\FileData;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  
    public function getBeneficiariesPerMonth($year)
    {
        $benesPerMonth = FileData::select(
                DB::raw('MONTH(date_time_claimed) as month'),
                DB::raw('YEAR(date_time_claimed) as year'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('date_time_claimed', $year) // Filter by year
            ->groupBy(DB::raw('MONTH(date_time_claimed)'), DB::raw('YEAR(date_time_claimed)'))
            ->orderBy(DB::raw('MONTH(date_time_claimed)'), 'ASC')
            ->get();

        $formattedBenes = $benesPerMonth->map(function ($item) {
            $monthName = Carbon::createFromFormat('m', $item->month)->format('F');
            return [
                'year' => $item->year,
                'month' => $monthName,
                'beneficiaries_count' => $item->count,
            ];
        });

        return response()->json($formattedBenes);
    }

    public function getCashAdvancePerMonth($year)
    {
        $cashAdvanceData = CashAdvance::select(
                DB::raw('MONTH(cash_advance_date) as month'),
                DB::raw('YEAR(cash_advance_date) as year'),
                DB::raw('SUM(cash_advance_amount) as total_amount')
            )
            ->whereYear('cash_advance_date', $year)
            ->groupBy(DB::raw('MONTH(cash_advance_date)'), DB::raw('YEAR(cash_advance_date)'))
            ->orderBy(DB::raw('MONTH(cash_advance_date)'), 'ASC')
            ->get();
    
        // Ensure all 12 months are included (even with 0 total)
        $allMonths = collect(range(1, 12))->map(function ($month) use ($cashAdvanceData) {
            $data = $cashAdvanceData->firstWhere('month', $month);
            return [
                'month' => Carbon::create()->month($month)->format('F'),
                'total_amount' => $data ? $data->total_amount : 0,
            ];
        });
    
        return response()->json($allMonths);
    }

    public function getSDOStatusPerMonth($year)
    {
        $data = CashAdvance::selectRaw("
                MONTH(cash_advance_date) as month, 
                SUM(CASE WHEN status = 'Liquidated' THEN 1 ELSE 0 END) as liquidated_count,
                SUM(CASE WHEN status = 'Unliquidated' THEN 1 ELSE 0 END) as unliquidated_count
            ")
            ->whereYear('cash_advance_date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Format output for all 12 months
        $formattedData = collect(range(1, 12))->map(function ($month) use ($data) {
            $record = $data->firstWhere('month', $month);
            return [
                'month' => Carbon::create()->month($month)->format('F'),
                'liquidated' => $record->liquidated_count ?? 0,
                'unliquidated' => $record->unliquidated_count ?? 0,
            ];
        });

        return response()->json($formattedData);
    }

    public function getTotalBeneficiaries($year)
    {
        $totalBeneficiaries = FileData::whereYear('date_time_claimed', $year)->count();
    
        return response()->json(['total_beneficiaries' => $totalBeneficiaries]);
    }

    public function getTotalCashAdvances($year)
    {
        $totalCashAdvances = CashAdvance::whereYear('cash_advance_date', $year)
            ->sum('cash_advance_amount');

        $totalLiquidated = CashAdvance::whereYear('cash_advance_date', $year)
            ->where('status', 'Liquidated')
            ->sum('cash_advance_amount');

        $totalUnliquidated = $totalCashAdvances - $totalLiquidated;

        return response()->json([
            'total_cash_advances' => $totalCashAdvances,
            'total_liquidated' => $totalLiquidated,
            'total_unliquidated' => $totalUnliquidated,
        ]);
    }

    public function getCashAdvanceSummary($year)
    {
        $totalCashAdvances = CashAdvance::whereYear('cash_advance_date', $year)->count();
    
        // Count total liquidated cash advances
        $totalLiquidated = CashAdvance::whereYear('cash_advance_date', $year)
            ->where('status', 'Liquidated')
            ->count();
    
        // Count total unliquidated cash advances
        $totalUnliquidated = CashAdvance::whereYear('cash_advance_date', $year)
            ->where('status', 'Unliquidated')
            ->count();
    
        // Calculate percentages (avoid division by zero)
        $liquidatedPercentage = $totalCashAdvances > 0 ? ($totalLiquidated / $totalCashAdvances) * 100 : 0;
        $unliquidatedPercentage = $totalCashAdvances > 0 ? ($totalUnliquidated / $totalCashAdvances) * 100 : 0;
    
        return response()->json([
            'year' => $year,
            'total_cash_advances_number' => $totalCashAdvances,
            'total_liquidated_number' => $totalLiquidated,
            'total_unliquidated_number' => $totalUnliquidated,
            'liquidated_percentage' => round($liquidatedPercentage, 2),
            'unliquidated_percentage' => round($unliquidatedPercentage, 2),
        ]);
    }
    

    



}

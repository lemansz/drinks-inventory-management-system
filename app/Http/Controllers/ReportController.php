<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Sale;
use App\Services\StockService;
use Barryvdh\DomPDF\Facade\Pdf;


class ReportController extends Controller {

public function downloadReport(Request $request, StockService $stockService)
{
    
    $dateInput = $request->query('date', today()->toDateString());
    $reportDate = Carbon::parse($dateInput);

    $sales = Sale::with('products')
        ->whereDate('created_at', $reportDate)
        ->latest()
        ->get();

    $totalSalesAmount = $sales->sum('total_amount'); 
    $totalProfit = $sales->sum('total_profit');
    $currency = $stockService->currency();

    $pdf = Pdf::loadView('emails.auth.reports.daily-sales-pdf', [
        'sales'       => $sales,
        'totalSales'  => $totalSalesAmount,
        'totalProfit' => $totalProfit,
        'date'        => $reportDate->format('M d, Y'),
        'currency'    => $currency
    ]);

    return $pdf->download('Report_' . $reportDate->format('Y-m-d') . '.pdf');
}

}

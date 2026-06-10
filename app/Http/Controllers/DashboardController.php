<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Services\StockService;
use App\Chart\WeekChart;
use App\Chart\MonthChart;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    private readonly StockService $stockService;
  
    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
     
    }

    public function index(Request $request, WeekChart $weekChart, MonthChart $monthChart)
    {

        $period = $request->query('period', 'week');

        if ($period === 'week') {
            $chart = $weekChart->build();
        } else {
            $chart = $monthChart->build();
        }


        $currency = $this->stockService->currency();

        $user = $request->user();

        
        // Fetch today's sales
        $todaysSales = Sale::whereDate('created_at', today('Africa/Lagos'))
            ->with('products')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('dashboard', compact('user', 'todaysSales', 'currency', 'chart', 'period'));
    }


}

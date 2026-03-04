<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Services\StockService;

class DashboardController extends Controller
{

    private readonly StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index()
    {
        $currency = $this->stockService->currency();
        $profit = Sale::sum('total_profit');
        $user = auth()->user();
        
        // Fetch today's sales
        $todaysSales = Sale::whereDate('created_at', today())
            ->with('products')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('dashboard', compact('profit', 'user', 'todaysSales', 'currency'));
    }


}

<?php

namespace App\Http\Controllers;

use App\Models\RestockLog;
use App\Services\StockService;

class RestockHistoryController extends Controller
{
    private readonly StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index()
    {
        $currency = $this->stockService->currency();
        $logs = RestockLog::with('product')->latest('restocked_at')->paginate(6);

        return view('restock-history.index', compact('logs', 'currency'));
    }

    public function show(RestockLog $log) 
    {
        $currency = $this->stockService->currency();
        $log->load('product');

        return view('restock-history.show', compact('log', 'currency'));
    }
}

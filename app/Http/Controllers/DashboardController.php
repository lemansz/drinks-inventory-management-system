<?php

namespace App\Http\Controllers;


use App\Models\Sale;



class DashboardController extends Controller
{
    public function index()
    {
        $profit = Sale::sum('total_profit');
        $user = auth()->user();
        
        // Fetch today's sales
        $todaysSales = Sale::whereDate('created_at', today())
            ->with('products')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard', compact('profit', 'user', 'todaysSales'));
    }


}

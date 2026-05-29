<?php

namespace App\Services;
use App\Models\Sale;

class SalesService
{
    public function grossProfit()
    {
       return Sale::sum('total_profit');
    }

    public function grossSales()
    {
       return Sale::sum('total_amount');
    }

    public function salesCount()
    {
        return Sale::count();
    }
}
<?php

namespace App\Services;
use App\Models\Product;
use App\Models\Setting;

class StockService
{

    public function currency(): string
    {
        return Setting::value('currency') ?? '₦';
    }

    public function hasLowStock(): bool
    {
        $lowStockThreshold = Setting::value('low_stock_threshold') ?? 10;

        return Product::where('stock','<', $lowStockThreshold)
        ->exists();
    }

    public function getLowStockProducts()
    {
        $lowStockThreshold = Setting::value('low_stock_threshold') ?? 10;

        return Product::with('category')
        ->where('stock','<', $lowStockThreshold)
        ->simplePaginate(8);
    }

    public function countStock(): int
    {
        return Product::count();
    }
    
    public function countLowStock(): int
    {
        $lowStockThreshold = Setting::value('low_stock_threshold') ?? 10;
        return Product::where('stock', '<', $lowStockThreshold)
        ->count();
    }

    public function threshold(): int
    {
        return Setting::value('low_stock_threshold') ?? 10;
    }
}

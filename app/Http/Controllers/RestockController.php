<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\StockService;
use App\Models\RestockLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class RestockController extends Controller
{
    private readonly StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index()
    {
        $getLowStock = $this->stockService->getLowStockProducts();
        $countLowStock = $this->stockService->countLowStock();
        $stockCount = $this->stockService->countStock();

        return view('restock.index', compact('getLowStock', 'countLowStock', 'stockCount'));
    }

    public function create(Product $product)
    {
        return view('restock.create', compact('product'));
    }

    public function store (Request $request, Product $product)
    {
        $data = $request->validate([
            '_idempotency_token' => ['required', 'string', 'uuid'],
            'crates'             => ['required', 'integer', 'min:1']
        ]);

        $token = $request->input('_idempotency_token');
        $expiry = Carbon::now()->addMinutes(10);

        //Prevent duplicate processing
        $cacheKey = "idempotency:product:{$token}";

        if (!Cache::add($cacheKey, true, $expiry))
        {
            return back()->withInput()->with('warning', 'You have already completed restock operation. Please check the product list');
        }

        $totalUnits = $data['crates'] * $product->pieces_per_crate;
        $totalCost = $totalUnits * $product->cost_per_unit;

        DB::transaction(function () use ($product, $data, $totalUnits, $totalCost){
            $product->increment('stock', $totalUnits);

            $product->restockLogs()->create([
                'crates' => $data['crates'],
                'units_per_crate' => $product->pieces_per_crate,
                'total_units' => $totalUnits,
                'unit_cost' => $product->cost_per_unit,
                'total_cost' => $totalCost,
            ]);
        });
 
        return redirect()->route('products.index')->with('success', "{$product->name} restocked successfully!");
    }

    public function search(Request $request)
    {
        $q = $request->input('q', '');
        
        $products = Product::with('category')
            ->where('stock', '<', 5)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('supplier', 'like', "%{$q}%")
                    ->orWhereHas('category', function ($categoryQuery) use ($q) {
                        $categoryQuery->where('name', 'like', "%{$q}%");
                    });
            })
            ->get();

        return response()->json($products);
    }

}

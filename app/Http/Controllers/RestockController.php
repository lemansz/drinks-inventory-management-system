<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\StockService;

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
            'crates' => 'required|integer|min:1'
        ]);

        $addedStock = $data['crates'] * $product->pieces_per_crate;

        $product->increment('stock', $addedStock);
 
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

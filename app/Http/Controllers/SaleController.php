<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\StockService;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class SaleController extends Controller
{
    private readonly StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index()
    {
        $currency = $this->stockService->currency();
        $sales = Sale::with('products')->latest()->paginate(6);
        return view('sales.index', compact('sales', 'currency'));
    }

    public function create()
    {
        $stockCount = $this->stockService->countStock();
        $currency = $this->stockService->currency();
        return view('sales.create', compact('stockCount', 'currency'));
    }

    public function show(Sale $sale)
    {
        $currency = $this->stockService->currency();
        $sale->load('products');
        return view('sales.show', compact('sale', 'currency'));
    }

    public function searchProducts(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (!$q)
        {
            return response()->json([]);
        }

        $products = Product::where('stock', '>', 0)
          ->where('name', 'like', "%{$q}%")
          ->select('id', 'name', 'stock', 'selling_price', 'supplier', 'pieces_per_crate')
          ->limit(10)
          ->get();

        return response()->json($products);
    }
    
    public function store(Request $request)
    {
        // Idempotency check
        $token = $request->input('_idempotency_token');
        $expiry = Carbon::now()->addMinutes(30);

        //Prevent duplicate processing
        $cacheKey = "idempotency:sale:{$token}";

        if (!$token || !Cache::add($cacheKey, true, $expiry))
        {
            return back()->withInput()->with('warning', 'This sale was already recorded successfully. Please check the sales list');
        }

        // Parse items from JSON string to array
        $items = json_decode($request->input('items'), true) ?? [];
        
        $validated = $request->validate([
            'notes' => 'nullable|string|max:500'
        ]);

        // Validate items manually
        if (empty($items)) {
            return back()->withErrors(['items' => 'Please add at least one product to the sale']);
        }

        foreach ($items as $item) {
            if (empty($item['product_id']) || !is_numeric($item['product_id'])) {
                return back()->withErrors(['error' => 'Invalid product']);
            }
            if (empty($item['quantity_sold']) || !is_numeric($item['quantity_sold']) || $item['quantity_sold'] <= 0) {
                return back()->withErrors(['error' => 'Invalid quantity']);
            }
        }

        try {
            // Create the sale record
            $sale = Sale::create([
                'total_amount' => 0,
                'notes' => $validated['notes'] ?? null
            ]);

            $totalAmount = 0;
            $totalProfit = 0;

            // Attach products to sale with pivot data
            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                // Check if enough stock available
                if ($product->stock < $item['quantity_sold']) {
                    return back()->withErrors([
                        'stock' => "Insufficient stock for {$product->name}. Available: {$product->stock}"
                    ]);
                }

                $pricePerUnit = $product->selling_price;
                $profitPerUnit = $product->profit;
                $subtotal = $item['quantity_sold'] * $pricePerUnit;
                $subProfit = $item['quantity_sold'] * $profitPerUnit;

                $totalProfit += $subProfit;
                $totalAmount += $subtotal;

                // Attach product to sale with pivot data
                $sale->products()->attach($product->id, [
                    'quantity_sold' => $item['quantity_sold'],
                    'price_per_unit' => $pricePerUnit,
                    'subtotal' => $subtotal
                ]);

                // Reduce product stock
                $product->decrement('stock', $item['quantity_sold']);
            }

            // Update total amount on sale
            $sale->update(['total_amount' => $totalAmount, 'total_profit' => $totalProfit]);
            $currency = $this->stockService->currency();

            return redirect()->route('sales.index')
                ->with('success', "Sale recorded successfully! Total: $currency" . number_format($totalAmount, 2));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error recording sale: ' . $e->getMessage()]);
        }
    }
}

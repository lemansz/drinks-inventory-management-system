<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Services\StockService;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ProductController extends Controller
{
    private readonly StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }
    
    public function index ()
    {
        $stockCount = $this->stockService->countStock();
        $stockThreshold = $this->stockService->threshold();
        $currency = $this->stockService->currency();

        $products = Product::with('category')->latest()->paginate(6);

        return view('products.index', compact('products', 'stockCount', 'stockThreshold', 'currency'));
    }
    
    public function show(Product $product)
    {
        $currency = $this->stockService->currency();
        return view('products.show', compact('product', 'currency'));
    }

    public function create()
    {
        $categories = Category::get(['id', 'name']);
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $token = $request->input('_idempotency_token');
        $expiry = Carbon::now()->addMinutes(30);

        //Prevent duplicate processing
        $cacheKey = "idempotency:product:{$token}";

        if (!$token || !Cache::add($cacheKey, true, $expiry))
        {
            return back()->withInput()->with('warning', 'This product was already added successfully. Please check the product list');
        }

        $validatedData = $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|integer',
            'cost_per_unit' => 'required|numeric|min:1',
            'selling_price' => 'required|numeric|gt:cost_per_unit',
            'crates_available' => 'required|integer|min:1',
            'pieces_per_crate' => 'required|integer|min:1',
            'supplier' => 'required|string|min:3',
            'supplier_phone_no' => 'required|numeric|digits:11',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'

        ], [], [
            'cost_per_unit' => 'cost',
            'selling_price' => 'selling price',
            'crates_available' => 'crates',
            'pieces_per_crate' => 'crate',
            'supplier_phone_no' => 'phone number'
        ]);

        if ($request->hasFile('photo')) {
            $validatedData['photo'] = $request->file('photo')->store('products', 'public');
        }

        $product = Product::create($validatedData);
        
        return redirect()->route('products.index')->with('success', "{$product->name} added successfully!");
    }

    public function edit(Product $product)
    {
        $categories = Category::get(['id', 'name']);

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'cost_per_unit' => 'required|numeric|min:1',
            'selling_price' => 'required|numeric|gt:cost_per_unit',
            'supplier' => 'required|string|min:3',
            'supplier_phone_no' => 'required|numeric|digits:11',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'required'
        ]);

        $product->update($validatedData);

        return redirect()->route('products.index')->with('success', "{$product->name}  updated successfully!");
    }
    
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', "{$product->name} deleted successfully!");
    }

    public function search(Request $request)
    {
        $q = $request->input('q', '');
        
        $products = Product::where('name', 'like', "%{$q}%")
            ->orWhereHas('category', function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%");
            })->with('category')
            ->get();

        return response()->json($products);
    }
}

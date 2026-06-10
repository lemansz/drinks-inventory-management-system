<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
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
    
    public function index (Request $request)
    {
        $stockCount = $this->stockService->countStock();
        $stockThreshold = $this->stockService->threshold();
        $currency = $this->stockService->currency();

        $sort      = $request->input('sort');
        $direction = $request->input('direction');

        $validSorts = ['cost_per_unit', 'selling_price', 'profit', 'stock', 'crates_remaining'];

        $query = Product::with('category');

        if (in_array($sort, $validSorts)){
            $dir = ($direction === 'desc') ? 'desc' : 'asc';

            if ($sort === 'crates_remaining') {
                $query->orderByRaw("CAST(stock / NULLIF(pieces_per_crate, 0) AS INTEGER) {$dir}");
            } elseif ($sort === 'profit') {
                $query->orderByRaw("(profit) {$dir}");
            } elseif ($sort === 'stock') {
                $query->orderByRaw("(stock) {$dir}");
            } elseif ($sort === 'selling_price') {
                $query->orderByRaw("(selling_price) {$dir}");
            } elseif ($sort === 'cost_per_unit') {
                $query->orderByRaw("(cost_per_unit) {$dir}");
            } else {
                $query->orderBy($sort, $dir);
            }
                $query->latest(); 
        } else {
            $query->latest();
        }

        $products = $query->paginate(8);

        if ($sort !== null){
            $products->appends([
                'sort' => $sort,
                'direction' => $direction
            ]);
        }

        
        return view('products.index', compact('products', 'sort', 'direction', 'stockCount', 'stockThreshold', 'currency'));
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

    public function store(StoreProductRequest $request)
    {
        $token = $request->input('_idempotency_token');
        $expiry = Carbon::now()->addMinutes(10);

        //Prevent duplicate processing
        $cacheKey = "idempotency:product:{$token}";

        if (!Cache::add($cacheKey, true, $expiry))
        {
            return back()->withInput()->with('warning', 'This product was already added successfully. Please check the product list');
        }

        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('products', 'public');
        }

        $product = Product::create($data);
        
        return redirect()->route('products.index')->with('success', "{$product->name} added successfully!");
    }

    public function edit(Product $product)
    {
        $categories = Category::get(['id', 'name']);

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        $product->update($data);

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

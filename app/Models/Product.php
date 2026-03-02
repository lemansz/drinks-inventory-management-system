<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'cost_per_unit',
        'selling_price',
        'profit',
        'crates_available',
        'stock',
        'pieces_per_crate',
        'supplier',
        'supplier_phone_no',
        'photo'
    ];

    protected static function booted()
    {
        static::creating( function ($product) {
            $product->profit = $product->selling_price - $product->cost_per_unit;
            $product->stock = $product->crates_available * $product->pieces_per_crate;
        });

        static::updating( function ($product) {
            $product->profit = $product->selling_price - $product->cost_per_unit;
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function sales(): BelongsToMany
    {
        return $this->belongsToMany(Sale::class, 'product_sale')
            ->withPivot('quantity_sold', 'price_per_unit', 'subtotal')
            ->withTimestamps();
    }
}
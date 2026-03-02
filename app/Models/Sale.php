<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sale extends Model
{
    protected $fillable = [
        'total_amount',
        'total_profit',
        'notes'
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_sale')
            ->withPivot('quantity_sold', 'price_per_unit', 'subtotal')
            ->withTimestamps();
    }

    
}

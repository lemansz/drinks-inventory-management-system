<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class RestockLog extends Model
{
    protected $fillable = [
        'product_id',
        'crates',
        'units_per_crate',
        'total_units',
        'unit_cost',
        'total_cost',
    ];

    protected $casts = [
        'restocked_at' => 'datetime'
    ];

    protected static function booted()
    {
    static::creating(function ($log) {
        $log->restocked_at = $log->restocked_at ?? now();
    });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

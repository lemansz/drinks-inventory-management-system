<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'currency',
        'low_stock_threshold',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

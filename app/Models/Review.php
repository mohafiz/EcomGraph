<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'review'
    ];

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);    
    }

    function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);    
    }
}

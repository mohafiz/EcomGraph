<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shippingDetails',
        'billingDetails',
        'totalPrice',
        'status_id'
    ];

    protected $casts = [
        'shippingDetails' => 'array',
        'billingDetails' => 'array',
        'payed' => 'boolean'
    ];

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');    
    }

    function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);    
    }
}

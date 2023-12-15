<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'price',
        'photo',
        'stock',
        'description',
        'raters',
        'rating_sum',
        'rating'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('stock', '>', 0);
    }

    function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity');    
    }

    function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    function wishlisted(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }

    function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}

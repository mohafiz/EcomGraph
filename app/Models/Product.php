<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'photo',
        'stock',
        'description'
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
}

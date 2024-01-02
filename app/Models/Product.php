<?php

namespace App\Models;

use App\Services\ExchangeRateService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use NumberFormatter;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'name_ar',
        'name_es',
        'name_fr',
        'price',
        'photo',
        'stock',
        'description',
        'description_ar',
        'description_es',
        'description_fr',
        'raters',
        'rating_sum',
        'rating'
    ];

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $this->getInUserCurrency($value),
        );
    }

    function getInUserCurrency($price)
    {
        if (auth('sanctum')->check()) {
            $locale   = User::find(auth('sanctum')->id())->language->code;
            $currency = User::find(auth('sanctum')->id())->currency->code;
        }
        else {
            $locale   = 'en';
            $currency = 'USD';
        }

        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        $converted = $currency == 'USD' ? $price :
            ExchangeRateService::convertCurrency($price, $currency);

        return $formatter->formatCurrency((float) $converted, $currency);
    }

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

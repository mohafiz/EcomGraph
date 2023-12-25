<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'verified',
        'chat_id',
        'code',
        'subscribed',
        'registered',
        'language_id',
        'currency_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'verified' => 'boolean',
        'subscribed' => 'boolean',
        'registered' => 'boolean'
    ];

    public function cart(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_user');
    }

    function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    function wishlist(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'wishlists');   
    }

    function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);    
    }

    function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);    
    }
}

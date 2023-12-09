<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        "code",
        "discountType",
        "discount",
        "minimumTotal",
        "startDate",
        "endDate"
    ];

    protected $casts = [
        'startDate' => 'date',
        'endDate' => 'date'
    ];
}

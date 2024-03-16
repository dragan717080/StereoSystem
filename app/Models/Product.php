<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Product extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'subtitle', 'reviews', 'price', 'price_group', 'stock_quantity'];

    public $timestamps = false;

    protected $casts = [
        'reviews' => 'string',
    ];

    public function getReviewsAttribute($value)
    {
        return json_decode($value, true);
    }
}

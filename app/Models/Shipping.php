<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Shipping extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['address', 'city', 'country', 'postal_code', 'shipped_date'];

    public $timestamps = false;
}

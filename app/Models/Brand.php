<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Brand extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'country', 'description'];

    // Or we could say in repository $brand->timestamps = false;
    public $timestamps = false;
}

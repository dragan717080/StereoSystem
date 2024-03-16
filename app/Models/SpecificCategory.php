<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SpecificCategory extends Model
{
    use HasFactory, HasUuids;

    # ImageSrc is src of image on nav, and banner is src of image on its own page
    protected $fillable = ['name', 'description', 'imageSrc', 'bannerImageSrc'];

    public $timestamps = false;
}

<?php

declare(strict_types = 1);

namespace App\Interfaces\SpecificCategory;

interface CreateInterface
{
    public function create(
        string $name,
        string $description,
        string $generalCategoryId,
        string $imageSrc,
    );
}

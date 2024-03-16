<?php

declare(strict_types = 1);

namespace App\Interfaces\DetailedCategory;

interface CreateInterface
{
    public function create(
        string $name,
        string $specificCategoryId,
    );
}

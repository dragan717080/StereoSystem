<?php

declare(strict_types = 1);

namespace App\Interfaces\Brand;

interface CreateInterface
{
    public function create(
        string $name,
        string $country,
        string $description,
    );
}

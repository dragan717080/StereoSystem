<?php

declare(strict_types = 1);

namespace App\Interfaces\Product;

interface CreateInterface
{
    public function create(
        string $name,
        string $subtitle,
        array $reviews,
        float $price,
        float $priceGroup,
        int $stockQuantity,
    );
}

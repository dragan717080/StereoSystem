<?php

declare(strict_types = 1);

namespace App\Interfaces\Product;

interface UpdateInterface
{
    public function update(
        string $id,
        ?string $name,
        ?string $subtitle,
        ?array $reviews,
        ?float $price,
        ?float $priceGroup,
        ?int $stockQuantity,
        ?int $vote,
    );
}

<?php

declare(strict_types = 1);

namespace App\Interfaces\SpecificCategory;

interface UpdateInterface
{
    public function update(
        string $id,
        ?string $orderId,
        ?string $name,
        ?string $description,
        ?string $imageSrc,
        ?string $generalCategoryId,
    );
}

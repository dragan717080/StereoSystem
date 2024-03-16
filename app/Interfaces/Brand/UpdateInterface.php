<?php

declare(strict_types = 1);

namespace App\Interfaces\Brand;

interface UpdateInterface
{
    public function update(
        string $id,
        ?string $name,
        ?string $country,
        ?string $description,
    );
}

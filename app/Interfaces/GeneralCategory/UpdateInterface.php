<?php

declare(strict_types = 1);

namespace App\Interfaces\GeneralCategory;

interface UpdateInterface
{
    public function update(
        string $id,
        ?string $name,
        ?string $description,
    );
}

<?php

declare(strict_types = 1);

namespace App\Interfaces\GeneralCategory;

interface CreateInterface
{
    public function create(
        string $name,
        string $description,
    );
}

<?php

declare(strict_types = 1);

namespace App\Interfaces\OrderItem;

interface CreateInterface
{
    public function create(
        string $customerId,
        string $status
    );
}

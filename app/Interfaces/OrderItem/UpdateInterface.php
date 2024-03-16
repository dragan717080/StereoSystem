<?php

declare(strict_types = 1);

namespace App\Interfaces\OrderItem;

interface UpdateInterface
{
    public function update(
        string $id,
        ?string $customerId,
        ?string $status
    );
}

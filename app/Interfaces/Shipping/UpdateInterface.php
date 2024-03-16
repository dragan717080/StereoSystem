<?php

declare(strict_types = 1);

namespace App\Interfaces\Shipping;

interface UpdateInterface
{
    public function update(
        string $id,
        ?string $orderId,
        ?string $address,
        ?string $city,
        ?string $country,
        ?string $postalCode,
        ?string $shippedDate,
    );
}

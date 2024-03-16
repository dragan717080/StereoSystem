<?php

declare(strict_types = 1);

namespace App\Interfaces\Shipping;

interface CreateInterface
{
    public function create(
        string $orderId,
        string $address,
        string $city,
        string $country,
        string $postalCode,
        string $shippedDate,
    );
}

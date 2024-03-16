<?php

declare(strict_types = 1);

namespace App\Interfaces\Customer;

interface UpdateInterface
{
    public function update(
        string $id,
        ?string $name,
        ?string $phone,
        ?string $firebaseId,
        ?string $email,
        ?string $address,
    );
}

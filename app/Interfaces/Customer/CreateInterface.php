<?php

declare(strict_types = 1);

namespace App\Interfaces\Customer;

interface CreateInterface
{
    public function create(
        string $name,
        string $firebaseId,
        string $phone,
        string $email,
    );
}

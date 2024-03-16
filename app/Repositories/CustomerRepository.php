<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Repositories\Traits\{ GetByIdTrait, DeleteTrait };
use Illuminate\Database\Eloquent\Collection;

class CustomerRepository
{
    use GetByIdTrait;
    use DeleteTrait;

    public $model;

    public function __construct()
    {
        $this->model = new Customer();
    }

    public function getAll(): Collection
    {
        return Customer::all();
    }

    public function update(
        string $id,
        ?string $name,
        ?string $firebaseId,
        ?string $phone,
        ?string $email,
    ): ?Customer
    {
        $customer = $this->model->find($id);

        if (!$customer) {
            return null;
        }

        if ($name !== null) {
            $customer->name = $name;
        }

        if ($phone !== null) {
            $customer->phone = $phone;
        }

        if ($firebaseId !== null) {
            $customer->firebaseId = $firebaseId;
        }

        if ($email !== null) {
            $customer->email = $email;
        }

        $customer->save();

        return $customer;
    }

    public function create(
        string $name,
        string $firebaseId,
        string $phone,
        string $email,
    ): Customer
    {
        $customer = new Customer();

        $customer->name = $name;
        $customer->firebaseId = $firebaseId;
        $customer->phone = $phone;
        $customer->email = $email;

        $customer->save();

        return $customer;
    }
}

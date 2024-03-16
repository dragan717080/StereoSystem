<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Traits\{ GetByIdTrait, DeleteTrait };
use App\Repositories\CustomerRepository;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository
{
    use GetByIdTrait;
    use DeleteTrait;

    public $model;

    public function __construct(private CustomerRepository $customerRepository)
    {
        $this->model = new Order();
    }

    public function getAll(): Collection
    {
        return Order::all();
    }

    public function update(
        string $id,
        ?string $customerId,
        ?string $status,
    ): ?Order
    {
        $order = $this->model->find($id);

        if (!$order) {
            return null;
        }

        if ($customerId !== null) {
            $order->customerId = $customerId;
        }

        if ($status !== null) {
            $order->status = $status;
        }

        $order->save();

        return $order;
    }

    public function create(
        string $customerId,
        string $status,
    ): Order
    {
        $order = new Order();

        $customer = $this->customerRepository->getById($customerId);
        $order->customer_id = $customer->id;
        $order->status = $status;

        $order->save();

        return $order;
    }
}

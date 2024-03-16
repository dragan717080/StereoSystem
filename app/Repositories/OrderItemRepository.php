<?php

namespace App\Repositories;

use App\Models\OrderItem;
use App\Repositories\Traits\{ GetByIdTrait, DeleteTrait };
use App\Repositories\CustomerRepository;
use Illuminate\Database\Eloquent\Collection;

class OrderItemRepository
{
    use GetByIdTrait;
    use DeleteTrait;

    public $model;

    public function __construct(private CustomerRepository $customerRepository)
    {
        $this->model = new OrderItem();
    }

    public function getAll(): Collection
    {
        return OrderItem::all();
    }

    public function update(
        string $id,
        ?string $customerId,
        ?string $status,
    ): ?OrderItem
    {
        $OrderItem = $this->model->find($id);

        if (!$OrderItem) {
            return null;
        }

        if ($customerId !== null) {
            $OrderItem->customerId = $customerId;
        }

        if ($status !== null) {
            $OrderItem->status = $status;
        }

        $OrderItem->save();

        return $OrderItem;
    }

    public function create(
        string $customerId,
        string $status,
    ): OrderItem
    {
        $OrderItem = new OrderItem();

        $customer = $this->customerRepository->getById($customerId);
        $OrderItem->customer_id = $customer->id;
        $OrderItem->status = $status;

        $OrderItem->save();

        return $OrderItem;
    }
}

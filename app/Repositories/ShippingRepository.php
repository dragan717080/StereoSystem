<?php

namespace App\Repositories;

use App\Models\Shipping;
use App\Repositories\Traits\{ GetByIdTrait, DeleteTrait };
use App\Repositories\OrderRepository;
use Illuminate\Database\Eloquent\Collection;

class ShippingRepository
{
    use GetByIdTrait;
    use DeleteTrait;

    public $model;

    public function __construct(private OrderRepository $orderRepository)
    {
        $this->model = new Shipping();
    }

    public function getAll(): Collection
    {
        return Shipping::all();
    }

    public function update(
        string $id,
        ?string $address,
        ?string $city,
        ?string $country,
        ?string $postalCode,
        ?string $shippedDate,
    ): ?Shipping
    {
        $shipping = $this->model->find($id);

        if (!$shipping) {
            return null;
        }

        if ($address !== null) {
            $shipping->address = $address;
        }

        if ($city !== null) {
            $shipping->city = $city;
        }

        if ($country !== null) {
            $shipping->country = $country;
        }

        if ($postalCode !== null) {
            $shipping->postalCode = $postalCode;
        }

        if ($shippedDate !== null) {
            $shipping->shippedDate = $shippedDate;
        }

        $shipping->save();

        return $shipping;
    }

    public function create(
        string $orderId,
        string $address,
        string $city,
        string $country,
        string $postalCode,
        string $shippedDate,
    ): Shipping
    {
        $shipping = new Shipping();

        $customer = $this->orderRepository->getById($orderId);
        $shipping->customer_id = $customer->id;
        $shipping->address = $address;
        $shipping->city = $city;
        $shipping->country = $country;
        $shipping->postalCode = $postalCode;
        $shipping->shippedDate = $shippedDate;

        $shipping->save();

        return $shipping;
    }
}

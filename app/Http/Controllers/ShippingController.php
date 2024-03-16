<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseModelController;
use App\Repositories\ShippingRepository;

class ShippingController extends BaseModelController
{
    protected $responseBuilder;

    public function __construct(protected ShippingRepository $shippingRepository) {
        parent::__construct($this->shippingRepository);
    }

    public function create(Request $req)
    {
        return $this->responseBuilder->postResponse(
            $req->request->all(),
            ['orderId', 'address', 'city', 'country', 'postalCode', 'shippedDate']
        );
    }

    public function update(string $id, Request $req)
    {
        return $this->responseBuilder->updateResponse(
            $id,
            $req->request->all(),
            ['orderId', 'address', 'city', 'country', 'postalCode', 'shippedDate']
        );
    }
}

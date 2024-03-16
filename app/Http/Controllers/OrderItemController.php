<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseModelController;
use App\Repositories\OrderItemRepository;

class OrderItemController extends BaseModelController
{
    protected $responseBuilder;

    public function __construct(protected OrderItemRepository $orderItemRepository) {
        parent::__construct($this->orderItemRepository);
    }

    public function create(Request $req)
    {
        return $this->responseBuilder->postResponse(
            $req->request->all(),
            ['customerId', 'status']
        );
    }

    public function update(string $id, Request $req)
    {
        return $this->responseBuilder->updateResponse(
            $id,
            $req->request->all(),
            ['customerId', 'status']
        );
    }
}

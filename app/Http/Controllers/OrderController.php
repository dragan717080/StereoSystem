<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseModelController;
use App\Repositories\OrderRepository;

class OrderController extends BaseModelController
{
    protected $responseBuilder;

    public function __construct(protected OrderRepository $orderRepository) {
        parent::__construct($this->orderRepository);
    }

    public function create(Request $req)
    {
        return $this->responseBuilder->postResponse(
            $req->request->all(),
            ['name', 'country', 'description']
        );
    }

    public function update(string $id, Request $req)
    {
        return $this->responseBuilder->updateResponse(
            $id,
            $req->request->all(),
            ['name', 'country', 'description']
        );
    }
}

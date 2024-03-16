<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseModelController;
use App\Repositories\CustomerRepository;

class CustomerController extends BaseModelController
{
    protected $responseBuilder;

    public function __construct(protected CustomerRepository $customerRepository) {
        parent::__construct($this->customerRepository);
    }

    public function create(Request $req)
    {
        return $this->responseBuilder->postResponse(
            $req->request->all(),
            ['name', 'firebaseId', 'phone', 'email']
        );
    }

    public function update(string $id, Request $req)
    {
        return $this->responseBuilder->updateResponse(
            $id,
            $req->request->all(),
            ['name', 'firebaseId', 'phone', 'email']
        );
    }
}

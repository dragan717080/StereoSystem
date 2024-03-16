<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseModelController;
use App\Repositories\ProductRepository;

class ProductController extends BaseModelController
{
    protected $responseBuilder;

    public function __construct(protected ProductRepository $productRepository) {
        parent::__construct($this->productRepository);
    }

    public function create(Request $req)
    {
        return $this->responseBuilder->postResponse(
            $req->request->all(),
            ['name', 'subtitle', 'reviews', 'price', 'priceGroup', 'stockQuantity']
        );
    }

    public function update(string $id, Request $req)
    {
        return $this->responseBuilder->updateResponse(
            $id,
            $req->request->all(),
            ['name', 'subtitle', 'description', 'price', 'priceGroup', 'stockQuantity']
        );
    }
}

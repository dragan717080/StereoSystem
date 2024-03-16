<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseModelController;
use App\Repositories\BrandRepository;

class BrandController extends BaseModelController
{
    protected $responseBuilder;

    public function __construct(protected BrandRepository $brandRepository) {
        parent::__construct($this->brandRepository);
    }

    public function create(Request $req)
    {
        return $this->responseBuilder->postResponse(
            $req->request->all(),
            ['name', 'country', 'description'],
            true,
        );
    }

    public function bulkAdd(Request $req)
    {
        return $this->responseBuilder->postManyResponse(
            $req->request->all(),
            ['name', 'country', 'description'],
            true,
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

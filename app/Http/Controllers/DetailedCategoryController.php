<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseModelController;
use App\Repositories\DetailedCategoryRepository;

class DetailedCategoryController extends BaseModelController
{
    protected $responseBuilder;

    public function __construct(protected DetailedCategoryRepository $detailedCategoryRepository) {
        parent::__construct($this->detailedCategoryRepository);
    }

    public function create(Request $req)
    {
        return $this->responseBuilder->postResponse(
            $req->request->all(),
            ['name', 'specificCategoryId']
        );
    }

    public function update(string $id, Request $req)
    {
        return $this->responseBuilder->updateResponse(
            $id,
            $req->request->all(),
            ['name', 'specificCategoryId']
        );
    }
}

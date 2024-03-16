<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseModelController;
use App\Repositories\GeneralCategoryRepository;

class GeneralCategoryController extends BaseModelController
{
    protected $responseBuilder;

    public function __construct(protected GeneralCategoryRepository $generalCategoryRepository) {
        parent::__construct($this->generalCategoryRepository);
    }

    public function create(Request $req)
    {
        return $this->responseBuilder->postResponse(
            $req->request->all(),
            ['name', 'description', 'imageSrc']
        );
    }

    public function update(string $id, Request $req)
    {
        return $this->responseBuilder->updateResponse(
            $id,
            $req->request->all(),
            ['name', 'description', 'imageSrc']
        );
    }
}

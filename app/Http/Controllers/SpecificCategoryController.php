<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseModelController;
use App\Repositories\SpecificCategoryRepository;

class SpecificCategoryController extends BaseModelController
{
    protected $responseBuilder;

    public function __construct(protected SpecificCategoryRepository $specificCategoryRepository) {
        parent::__construct($this->specificCategoryRepository);
    }

    public function create(Request $req)
    {
        return $this->responseBuilder->postResponse(
            $req->request->all(),
            ['name', 'description', 'imageSrc', 'bannerImageSrc', 'generalCategoryId']
        );
    }

    public function update(string $id, Request $req)
    {
        return $this->responseBuilder->updateResponse(
            $id,
            $req->request->all(),
            ['name', 'description', 'imageSrc', 'bannerImageSrc', 'generalCategoryId']
        );
    }
}

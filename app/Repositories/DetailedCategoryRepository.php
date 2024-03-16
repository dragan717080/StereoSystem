<?php

namespace App\Repositories;

use App\Models\DetailedCategory;
use App\Repositories\Traits\{ GetByIdTrait, DeleteTrait };
use App\Repositories\SpecificCategoryRepository;
use Illuminate\Database\Eloquent\Collection;

class DetailedCategoryRepository
{
    use GetByIdTrait;
    use DeleteTrait;

    public $model;

    public function __construct(private SpecificCategoryRepository $specificCategoryRepository)
    {
        $this->model = new DetailedCategory();
    }

    public function getAll(): Collection
    {
        return DetailedCategory::all();
    }

    public function update(
        string $id,
        ?string $name,
        ?string $specificCategoryId,
    ): ?DetailedCategory
    {
        $detailedCategory = $this->model->find($id);

        if (!$detailedCategory) {
            return null;
        }

        if ($specificCategoryId) {
            $specificCategory = $this->specificCategoryRepository->getById($specificCategoryId);

            if (!$specificCategory) {
                return 'Specific category with this id does not exist';
            }

            $specificCategory->specific_category_id = $specificCategory->id;
        }

        if ($name !== null) {
            $detailedCategory->name = $name;
        }

        $detailedCategory->save();

        return $detailedCategory;
    }

    public function create(
        string $name,
        string $specificCategoryId,
    ): DetailedCategory
    {
        $detailedCategory = new DetailedCategory();

        $specificCategory = $this->specificCategoryRepository->getById($specificCategoryId);
        if (!$specificCategory) {
            return 'Specific category with this id does not exist';
        }

        $detailedCategory->specific_category_id = $specificCategory->id;

        $detailedCategory->name = $name;

        $detailedCategory->save();

        return $detailedCategory;
    }
}

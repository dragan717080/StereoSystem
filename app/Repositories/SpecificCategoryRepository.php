<?php

namespace App\Repositories;

use App\Models\SpecificCategory;
use App\Repositories\Traits\{ GetByIdTrait, DeleteTrait };
use App\Repositories\GeneralCategoryRepository;
use Illuminate\Database\Eloquent\Collection;

class SpecificCategoryRepository
{
    use GetByIdTrait;
    use DeleteTrait;

    public $model;

    public function __construct(private GeneralCategoryRepository $generalCategoryRepository)
    {
        $this->model = new SpecificCategory();
    }

    public function getAll(): Collection
    {
        return SpecificCategory::all();
    }

    public function update(
        string $id,
        ?string $name,
        ?string $description,
        ?string $imageSrc,
        ?string $bannerImageSrc,
        ?string $generalCategoryId,
    ): ?SpecificCategory
    {
        $specificCategory = $this->model->find($id);

        if (!$specificCategory) {
            return null;
        }

        if ($generalCategoryId) {
            $generalCategory = $this->generalCategoryRepository->getById($generalCategoryId);

            if (!$generalCategory) {
                return 'General category with this id does not exist';
            }

            $specificCategory->general_category_id = $generalCategory->id;
        }

        if ($name !== null) {
            $specificCategory->name = $name;
        }

        if ($description !== null) {
            $specificCategory->description = $description;
        }

        if ($imageSrc !== null) {
            $specificCategory->image_src = $imageSrc;
        }

        if ($bannerImageSrc !== null) {
            $specificCategory->banner_image_src = $bannerImageSrc;
        }

        $specificCategory->save();

        return $specificCategory;
    }

    public function create(
        string $name,
        string $description,
        string $imageSrc,
        string $bannerImageSrc,
        string $generalCategoryId,
    ): SpecificCategory
    {
        $specificCategory = new SpecificCategory();

        $generalCategory = $this->generalCategoryRepository->getById($generalCategoryId);
        if (!$generalCategory) {
            return 'General category with this id does not exist';
        }

        $specificCategory->general_category_id = $generalCategory->id;

        $specificCategory->name = $name;
        $specificCategory->description = $description;
        $specificCategory->image_src = $imageSrc;
        $specificCategory->banner_image_src = $bannerImageSrc;

        $specificCategory->save();

        return $specificCategory;
    }
}

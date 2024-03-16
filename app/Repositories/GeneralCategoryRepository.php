<?php

namespace App\Repositories;

use App\Models\GeneralCategory;
use App\Repositories\Traits\{ GetByIdTrait, DeleteTrait };
use Illuminate\Database\Eloquent\Collection;

class GeneralCategoryRepository
{
    use GetByIdTrait;
    use DeleteTrait;

    public $model;

    public function __construct()
    {
        $this->model = new GeneralCategory();
    }

    public function getAll(): Collection
    {
        return GeneralCategory::all();
    }

    public function update(
        string $id,
        ?string $name,
        ?string $description,
        ?string $imageSrc,
    ): ?GeneralCategory
    {
        $generalCategory = $this->model->find($id);

        if (!$generalCategory) {
            return null;
        }

        if ($name !== null) {
            $generalCategory->name = $name;
        }

        if ($description !== null) {
            $generalCategory->description = $description;
        }

        if ($imageSrc !== null) {
            $generalCategory->imageSrc = $imageSrc;
        }

        $generalCategory->save();

        return $generalCategory;
    }

    public function create(
        string $name,
        string $description,
        string $imageSrc,
    ): GeneralCategory
    {
        $generalCategory = new GeneralCategory();

        $generalCategory->name = $name;
        $generalCategory->description = $description;
        $generalCategory->imageSrc = $imageSrc;

        $generalCategory->save();

        return $generalCategory;
    }
}

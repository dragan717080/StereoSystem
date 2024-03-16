<?php

namespace App\Repositories;

use App\Models\Brand;
use App\Repositories\Traits\{ GetByIdTrait, DeleteTrait };
use Illuminate\Database\QueryException;

class BrandRepository
{
    use GetByIdTrait;
    use DeleteTrait;

    public $model;

    public function __construct()
    {
        $this->model = new Brand();
    }

    public function getAll()
    {
        return Brand::all();
    }

    public function update(
        string $id,
        ?string $name,
        ?string $country,
        ?string $description,
    )
    {
        $brand = $this->model->find($id);

        if (!$brand) {
            return null;
        }

        if ($name !== null) {
            $brand->name = $name;
        }

        if ($country !== null) {
            $brand->country = $country;
        }

        if ($description !== null) {
            $brand->description = $description;
        }

        $brand->save();

        return $brand;
    }

    public function create(
        string $name,
        string $country,
        string $description,
    )
    {
        $brand = new Brand();

        $brand->name = $name;
        $brand->country = $country;
        $brand->description = $description;

        $brand->save();

        return $brand;
    }

    public function createMany(array $brandsData)
    {
        $brands = [];

        // Enumerate through list of data
        foreach (array_values($brandsData) as $i=>$data) {
            $brand = new Brand();

            try {
                try {
                    $brand->name = $data['name'];
                    $brand->country = $data['country'];
                    $brand->description = $data['description'];

                } catch (\ErrorException $e) {
                    // Always store in var if want to use end()
                    $parts = explode(" ", $e->getMessage());
                    $keyName = str_replace('"', '', end($parts));
                    return "Failed at index $i: Missing key " . $keyName;
                }

                $brand->save();
            } catch (QueryException $e) {
                // Unique key error, return type is string
                if ($e->getCode() === "23505") {
                    if (preg_match('/Key \((.*?)\)/', $e->getMessage(), $matches)) {
                        $failedForKey = $matches[1];
                        $failedForValue = $data[$failedForKey];
                        return "Failed at index $i: Unique constraint failed for key " . $failedForKey . " and value $failedForValue";
                    }
                }
            }

            $brands[] = $brand;
        }

        return $brands;
    }
}

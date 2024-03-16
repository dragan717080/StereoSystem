<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Traits\{ GetByIdTrait, DeleteTrait };
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    use GetByIdTrait;
    use DeleteTrait;

    public $model;

    public function __construct()
    {
        $this->model = new Product();
    }

    public function getAll(): Collection
    {
        return Product::all();
    }

    public function update(
        string $id,
        ?string $name, 
        ?string $subtitle,
        ?array $reviews,
        ?float $price,
        ?float $priceGroup,
        ?int $stockQuantity,
        ?int $vote,
    ): ?Product
    {
        $product = $this->model->find($id);

        if (!$product) {
            return null;
        }

        if ($name !== null) {
            $product->name = $name;
        }

        if ($subtitle !== null) {
            $product->subtitle = $subtitle;
        }

        if ($reviews !== null) {
            $product->reviews = $reviews;
        }

        if ($price !== null) {
            $product->price = sprintf("%.2f", $price);
        }

        if ($priceGroup !== null) {
            $product->price_group = sprintf("%.2f", $priceGroup);
        }

        if ($stockQuantity !== null) {
            $product->stock_quantity = $stockQuantity;
        }

        // Increse the number of votes and update rating
        if ($vote !== null) {
            $rating = round($product->rating * $product->total_votes) + $vote;
            $product->total_votes += 1;
            $product->rating = sprintf("%.2f", round($rating / $product->total_votes));
        }

        $product->save();

        return $product;
    }

    public function create(
        string $name,
        string $subtitle,
        array $description,
        float $price,
        float $priceGroup,
        int $stockQuantity,
    ): Product
    {
        $product = new Product();

        $product->name = $name;
        $product->subtitle = $subtitle;
        $product->description = json_encode($description);
        $product->price = sprintf("%.2f", $price);
        $product->price_group = sprintf("%.2f", $priceGroup);
        $product->stock_quantity = $stockQuantity;
        $product->rating = 0;
        $product->total_votes = 0;

        $product->save();

        return $product;
    }
}

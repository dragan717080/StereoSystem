<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('subtitle');
            $table->json('reviews');
            $table->float('price');
            // Price when bought in group will be with a discount
            $table->float('price_group');
            $table->integer('stock_quantity');
            $table->float('rating');
            $table->integer('total_votes');

            $table->foreignUuid('category_id')->references('id')->on('detailed_categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

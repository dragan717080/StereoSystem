<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('specific_categories', function(Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('image_src');
            $table->string('banner_image_src');
            $table->text('description');

            $table->foreignUuid('general_category_id')->references('id')->on('general_categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specific_categories');
    }
};

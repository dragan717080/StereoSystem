<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detailed_categories', function(Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();

            $table->foreignUuid('specific_category_id')->references('id')->on('specific_categories')->onDelete('cascade');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('detailed_categories');
    }
};

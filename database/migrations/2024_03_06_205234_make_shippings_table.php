<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shippings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('address');
            $table->string('city');
            $table->string('country');
            $table->integer('postal_code');
            $table->date('shipped_date');

            $table->foreignUuid('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shippings');
    }
};

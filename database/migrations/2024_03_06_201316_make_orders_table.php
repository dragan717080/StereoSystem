<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('status', [
                'Pending',
                'Processing',
                'Shipped',
                'Delivered',
                'Cancelled',
                'Refunded',
                'On Hold',
                'Completed',
                'Failed'
            ])->default('Pending');

            $table->timestamps();

            $table->foreignUuid('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

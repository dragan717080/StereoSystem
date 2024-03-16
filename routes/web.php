<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CustomerController,
    BrandController,
    GeneralCategoryController,
    SpecificCategoryController,
    DetailedCategoryController,  
    ProductController,
    OrderController,
    OrderItemController,
    ShippingController,
};

Route::prefix('customers')->group(function() {
    Route::get('/', [CustomerController::class, 'getAll']);
    Route::get('/{id}', [CustomerController::class, 'getById']);
    Route::post('/', [CustomerController::class, 'create']);
    Route::patch('/{id}', [CustomerController::class, 'update']);
    Route::delete('/{id}', [CustomerController::class, 'delete']);
});

Route::prefix('brands')->group(function() {
    Route::get('/', [BrandController::class, 'getAll']);
    Route::get('/{id}', [BrandController::class, 'getById']);
    Route::post('/', [BrandController::class, 'create']);
    Route::patch('/{id}', [BrandController::class, 'update']);
    Route::delete('/{id}', [BrandController::class, 'delete']);
    Route::post('/bulk-add', [BrandController::class, 'bulkAdd']);
});

Route::prefix('general-categories')->group(function() {
    Route::get('/', [GeneralCategoryController::class, 'getAll']);
    Route::get('/{id}', [GeneralCategoryController::class, 'getById']);
    Route::post('/', [GeneralCategoryController::class, 'create']);
    Route::patch('/{id}', [GeneralCategoryController::class, 'update']);
    Route::delete('/{id}', [GeneralCategoryController::class, 'delete']);
});

Route::prefix('specific-categories')->group(function() {
    Route::get('/', [SpecificCategoryController::class, 'getAll']);
    Route::get('/{id}', [SpecificCategoryController::class, 'getById']);
    Route::post('/', [SpecificCategoryController::class, 'create']);
    Route::patch('/{id}', [SpecificCategoryController::class, 'update']);
    Route::delete('/{id}', [SpecificCategoryController::class, 'delete']);
});

Route::prefix('detailed-categories')->group(function() {
    Route::get('/', [DetailedCategoryController::class, 'getAll']);
    Route::get('/{id}', [DetailedCategoryController::class, 'getById']);
    Route::post('/', [DetailedCategoryController::class, 'create']);
    Route::patch('/{id}', [DetailedCategoryController::class, 'update']);
    Route::delete('/{id}', [DetailedCategoryController::class, 'delete']);
});

Route::prefix('products')->group(function() {
    Route::get('/', [ProductController::class, 'getAll']);
    Route::get('/{id}', [ProductController::class, 'getById']);
    Route::post('/', [ProductController::class, 'create']);
    Route::patch('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'delete']);
});

Route::prefix('orders')->group(function() {
    Route::get('/', [OrderController::class, 'getAll']);
    Route::get('/{id}', [OrderController::class, 'getById']);
    Route::post('/', [OrderController::class, 'create']);
    Route::patch('/{id}', [OrderController::class, 'update']);
    Route::delete('/{id}', [OrderController::class, 'delete']);
});

Route::prefix('order-items')->group(function() {
    Route::get('/', [OrderItemController::class, 'getAll']);
    Route::get('/{id}', [OrderItemController::class, 'getById']);
    Route::post('/', [OrderItemController::class, 'create']);
    Route::patch('/{id}', [OrderItemController::class, 'update']);
    Route::delete('/{id}', [OrderItemController::class, 'delete']);
});

Route::prefix('shippings')->group(function() {
    Route::get('/', [ShippingController::class, 'getAll']);
    Route::get('/{id}', [ShippingController::class, 'getById']);
    Route::post('/', [ShippingController::class, 'create']);
    Route::patch('/{id}', [ShippingController::class, 'update']);
    Route::delete('/{id}', [ShippingController::class, 'delete']);
});

Route::get('/', function () {
    return view('welcome');
});

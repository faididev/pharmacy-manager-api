<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\CategoryController;


Route::apiResource('products', ProductController::class);
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);


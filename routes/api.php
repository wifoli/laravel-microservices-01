<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    CategoryController,
    CompanyController,
};

Route::get('/', function () {
    return response()->json(['message' => 'success']);
});

Route::apiResource('companies', CompanyController::class);
Route::apiResource('categories', CategoryController::class);

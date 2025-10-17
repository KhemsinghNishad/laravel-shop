<?php

use App\Http\Controllers\api\ProductApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('/product', [ProductApiController::class, 'index'])->name('products');

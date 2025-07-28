<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PromotionRules\RulesController;
use App\Http\Controllers\Api\PromotionRules\RulesEvaluationController;
use App\Http\Controllers\Api\CustomersController;
use App\Http\Controllers\Api\ProductsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('rules', RulesController::class);

Route::post('/evaluate', [RulesEvaluationController::class, 'evaluate']);

Route::get('/customers', [CustomersController::class, 'index']);

Route::get('/products', [ProductsController::class, 'index']);

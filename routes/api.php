<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
    Route::get("/articles/preferences", [\App\Http\Controllers\Api\ArticleController::class, 'getArticlesByPreferences']);
})->middleware('auth:sanctum');



Route::get("/articles", [\App\Http\Controllers\Api\ArticleController::class, 'getArticles']);



<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/user/create', [\App\Http\Controllers\api\UserController::class, 'store']);

Route::get("/articles", [\App\Http\Controllers\Api\ArticleController::class, 'getArticles']);
Route::post("/user-settings/store", [\App\Http\Controllers\Api\UserSettingsController::class, 'store']);
Route::get("/user-settings/preferences", [\App\Http\Controllers\Api\UserSettingsController::class, 'getPreferences']);



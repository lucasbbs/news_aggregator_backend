<?php

use App\Http\Controllers\Api\LatestNewsController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\SourceCategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::get('', [\App\Http\Controllers\Api\UserController::class, 'user']);
    Route::put('/password', [\App\Http\Controllers\Api\UserController::class, 'updatePassword']);
    Route::put('/name', [\App\Http\Controllers\Api\UserController::class, 'updateName']);
});

Route::prefix('auth')->group(function () {
    Route::post('login', [\App\Http\Controllers\Api\UserController::class, 'login']);
    Route::post('register', [\App\Http\Controllers\Api\UserController::class, 'register']);
    Route::middleware('auth:sanctum')->post('logout', [\App\Http\Controllers\Api\UserController::class, 'logout']);
});

Route::prefix('news')->group(function () {
    Route::get('latest', [LatestNewsController::class, 'index']);
    Route::get('nytimes', [NewsController::class, 'getNewYorkTimesNews']);
    Route::get('guardian',[NewsController::class, 'getTheGuardianNews']); 
    Route::get('newsapi', [NewsController::class, 'getNewsApiNews']);
});

Route::get('sources', [SourceCategoryController::class, 'index']);
Route::middleware('auth:sanctum')->get('sources/favorites', [SourceCategoryController::class, 'getUserFavoritesSources']);

Route::prefix('favorites')->middleware('auth:sanctum')->group(function () {
    Route::post('user-favorites', [\App\Http\Controllers\Api\UserFavoritesController::class, 'store']);
});

Route::prefix('tags')->middleware('auth:sanctum')->group(function () {
    Route::get('', [\App\Http\Controllers\Api\TagController::class, 'index']);
    Route::post('', [\App\Http\Controllers\Api\TagController::class, 'store']);
    Route::delete('{id}', [\App\Http\Controllers\Api\TagController::class, 'delete']);
});

Route::prefix('settings')->middleware('auth:sanctum')->group(function () {
    Route::get('', [\App\Http\Controllers\Api\UserSettingsController::class, 'index']);
    Route::post('', [\App\Http\Controllers\Api\UserSettingsController::class, 'store']);
});
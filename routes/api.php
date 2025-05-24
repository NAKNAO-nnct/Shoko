<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Assets\AssetStoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', fn (Request $request) => $request->user())->middleware('auth:sanctum');

Route::group(['prefix' => 'v1', 'as' => 'v1.'], function (): void {
    Route::get('/users', fn () => response()->json(['message' => 'Hello, World!']))->name('users.index');

    Route::group(['prefix' => 'assets', 'as' => 'assets.'], function (): void {
        Route::post('/', AssetStoreController::class)->name('store');
    });
});

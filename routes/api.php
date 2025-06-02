<?php

use app\Http\Controllers\v1\DownloadController;
use app\Http\Controllers\v1\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'v1'], function () {
    Route::post('/search', [SearchController::class, 'search']);
    Route::post('/download', [DownloadController::class, 'download']);
});
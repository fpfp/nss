<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TshirtController;
use App\Http\Controllers\DesignController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([], function(){
    Route::get('/tshirts', [TshirtController::class, 'index']);
    Route::get('/tshirts/{tshirt}', [TshirtController::class, 'show']);
    Route::post('/tshirts', [TshirtController::class, 'store']);
    Route::put('/tshirts/{tshirt}', [TshirtController::class, 'update']);
    Route::delete('/tshirts/{tshirt}', [TshirtController::class, 'destroy']);
});

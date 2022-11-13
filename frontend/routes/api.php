<?php

use App\Http\Controllers\CarValuePredictionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/car/makes', [CarValuePredictionController::class, 'makes']);
Route::get('/car/prediction', [CarValuePredictionController::class, 'index']);
Route::post('/car/prediction', [CarValuePredictionController::class, 'predict']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

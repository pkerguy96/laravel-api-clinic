<?php

use App\Http\Controllers\API\v1\AdminController;
use App\Http\Controllers\API\v1\AuthController;
use App\Http\Controllers\API\v1\PatientController;
use Illuminate\Http\Request;
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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\API\v1'], function () {
    route::post('/login', [AuthController::class, 'login']);
    route::get('/verify-token', [AuthController::class, 'Verifytoken']);
});
route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\API\v1', 'middleware' => 'auth:sanctum'], function () {
    Route::get('Admin/profile', [AdminController::class, 'getpicture']);
    Route::post('Admin/store/profile', [AdminController::class, 'storeprofile']);
    Route::post('Admin/update/profile', [AdminController::class, 'ModifyProfile']);

    route::apiResource('Patient', PatientController::class);
});

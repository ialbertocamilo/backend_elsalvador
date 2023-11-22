<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
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



Route::get('/hello',fn()=>'Test App apache time '.\Illuminate\Support\Facades\Date::now());
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/verify-token', [AuthController::class, 'verify'])->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register']);
Route::apiResource('projects', ProjectController::class)->middleware('auth:sanctum');
Route::group(['prefix' => 'projects', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/search', [\App\Http\Controllers\ProjectController::class, 'search']);
    Route::post('/get-data', [\App\Http\Controllers\ProjectController::class, 'getProjectData']);
    Route::post('/save-data', [\App\Http\Controllers\ProjectController::class, 'saveProjectData']);
    Route::post('/save-file', [\App\Http\Controllers\ProjectController::class, 'saveFiles']);
    Route::post('/get-files', [\App\Http\Controllers\ProjectController::class, 'getFiles']);
    Route::post('/download-file', [\App\Http\Controllers\ProjectController::class, 'downloadFile']);
    Route::post('/set-status', [\App\Http\Controllers\ProjectController::class, 'setStatus']);
});

Route::group(['prefix' => 'map', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/geojson',[\App\Http\Controllers\ProjectLocationController::class,'generateGeoJson']);
});
Route::apiResource('data', \App\Http\Controllers\DataController::class)->middleware('auth:sanctum');
Route::group(['prefix' => 'data', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/get-one', [\App\Http\Controllers\DataController::class, 'getBy']);
});
//Route::mediaLibrary();

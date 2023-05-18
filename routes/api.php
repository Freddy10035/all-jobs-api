<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\BrighterMondayJobController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// api/v1
Route::group([
    'prefix' => 'v1',
    'namespace' => 'App\Http\Controllers\Api\V1',
    'middleware' => ['client'],
], function () {
    Route::apiResource('jobs', BrighterMondayJobController::class);  // api/v1/jobs?page=1&perPage=5

    Route::get('totalJobCount', [BrighterMondayJobController::class,'getJobCount']);
    Route::get('jobs/location/{location}', [BrighterMondayJobController::class, 'getJobCountByLocation']);
    Route::get('search', [BrighterMondayJobController::class, 'searchJobs']);  //http://127.0.0.1:8000/api/v1/search?search=nairobi
    Route::get('groupByCategory', [BrighterMondayJobController::class, 'groupByCategory']);  //http://127.0.0.1:8000/api/v1/groupByCategory
    Route::get('jobs/byLocation/{location}', [BrighterMondayJobController::class, 'getByLocation']);
    Route::get('jobs/filterSearch', [BrighterMondayJobController::class, 'filterSearch']);
});

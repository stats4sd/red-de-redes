<?php

use App\Http\Controllers\Api\ComunidadController;
use App\Http\Controllers\Api\DepartamentoController;
use App\Http\Controllers\Api\MunicipioController;
use App\Http\Controllers\Api\ParcelaController;
use App\Http\Controllers\Api\StationController;
use App\Http\Controllers\Api\YearController;
use Illuminate\Http\Request;

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

Route::apiResource('comunidads', ComunidadController::class);
Route::apiResource('stations', StationController::class);
Route::apiResource('parcelas', ParcelaController::class);
Route::apiResource('departamentos', DepartamentoController::class);
Route::apiResource('municipios', MunicipioController::class);
Route::apiResource('years', YearController::class);

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\RequestController;
use App\Http\Controllers\Api\V1\ResiduosController;

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


Route::prefix('v1')->group(function(){
    Route::post('/store',[ResiduosController::class,'store']);
    Route::delete('/delete/{id}',[ResiduosController::class,'delete']);
    Route::put('/edit/{id}',[ResiduosController::class,'update']);
    Route::get('/status',[RequestController::class,'buscaStatus']);
    Route::get('/get',[ResiduosController::class,'index']);
});

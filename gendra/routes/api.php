<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EvidenceController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/evidenciasindex', [EvidenceController::class, 'index'])->middleware('auth:sanctum');

// Route::put('/evidencias/actualizar', 'Evidence@update');

// Route::post('/evidenciassotre', 'EvidenceController@store')->middleware('auth:sanctum');

// Route::delete('/evidencias/borrar/{id}', 'Evidence@destroy');

// Route::get('/evidencias/buscar', 'Evidence@show');

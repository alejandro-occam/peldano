<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Guardar o actualizar empresa desde Hubspot
Route::post('/save_companies_from_hubspot', [App\Http\Controllers\ExternalRequestController::class, 'saveCompaniesFromHubspot'])->name('save_companies_from_hubspot');
//Guardar o actualizar contacto desde Hubspot
Route::post('/save_contacts_from_hubspot', [App\Http\Controllers\ExternalRequestController::class, 'saveContactsFromHubspot'])->name('save_contacts_from_hubspot');
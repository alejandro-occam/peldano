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
//Enviar pdf de la propuesto a hubspot
Route::post('/save_deal_hubspot', [App\Http\Controllers\ExternalRequestController::class, 'saveDealFromHubspot'])->name('save_deal_hubspot');

//Login
Route::post('login', '\App\Http\Controllers\Auth\LoginController@loginapi');

 //Crear propuesta
 Route::post('/create_proposal', [App\Http\Controllers\Api\ProposalsInfoges::class, 'createProposal'])->name('create_proposal');

Route::group(['middleware' => ['jwt.verify']], function() {
   

    //Modificar propuesta
    Route::post('/update_proposal', [App\Http\Controllers\Api\ProposalsInfoges::class, 'updateProposal'])->name('update_proposal');
   
    //Eliminar propuesta
    Route::get('/delete_proposal/{id}', [App\Http\Controllers\Api\ProposalsInfoges::class, 'deleteProposal'])->name('delete_proposal');

    //Crear orden
    Route::post('/create_order', [App\Http\Controllers\Api\OrdersInfoges::class, 'createOrder'])->name('create_order');

    //Modificar orden
    Route::post('/update_order', [App\Http\Controllers\Api\OrdersInfoges::class, 'updateOrder'])->name('update_order');

    //Eliminar orden
    Route::get('/delete_order/{id}', [App\Http\Controllers\Api\OrdersInfoges::class, 'deleteOrder'])->name('delete_order');
    
});
<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
})->name('login');

//Recuperar-Nueva contraseña
Route::post('/send_email_reset_password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'sendEmailResetPassword'])->name('send_email_reset_password');
Route::get('/reset_password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'resetPassword'])->name('reset_password');
Route::post('/new_password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'newPassword'])->name('new_password');

Auth::routes();

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    //PROPOSALS
    //Listar propuestas
    Route::post('/list_proposals', [App\Http\Controllers\ProposalsController::class, 'listProposals'])->name('list_proposals');
    //Listar para el select de consultores
    Route::get('/get_users', [App\Http\Controllers\ProposalsController::class, 'getUsers'])->name('get_users');
    //Listar para el select de empresas
    Route::get('/get_companies', [App\Http\Controllers\ProposalsController::class, 'getCompanies'])->name('get_companies');
    //Guardar y generar una propuesta 
    Route::post('/save_generate_proposal', [App\Http\Controllers\ProposalsController::class, 'saveAndGenerateProposal'])->name('save_generate_proposal');
    //Generar pdf del presuspuesto TEST
    Route::get('/generate_pdf_proposal_test', [App\Http\Controllers\ProposalsController::class, 'generatePdfProposalTest'])->name('generate_pdf_proposal_test');
    //Consultar información de una propuesta
    Route::get('/get_info_proposal/{id}', [App\Http\Controllers\ProposalsController::class, 'getInfoProposal'])->name('get_info_proposal');
    //Actualizar propuesta 
    Route::post('/update_proposal', [App\Http\Controllers\ProposalsController::class, 'updateProposal'])->name('update_proposal');
    
    //END PROPOSALS


    //CONFIGURATION
    //USUARIOS
    //List users
    Route::post('/list_users', [App\Http\Controllers\ConfigurationController::class, 'listUsers'])->name('list_user');
    //Add user 
    Route::post('/add_user', [App\Http\Controllers\ConfigurationController::class, 'addUser'])->name('add_user');
    //Información necesaria para el formulario de usuarios
    Route::get('/get_info_form_add_user', [App\Http\Controllers\ConfigurationController::class, 'getInfoFormAddUser'])->name('get_info_form_add_user');
    //Consultar información de un usuario
    Route::get('/get_info_user/{id}', [App\Http\Controllers\ConfigurationController::class, 'getInfoUser'])->name('get_info_user');
    //Actualizar usuario
    Route::post('/update_user', [App\Http\Controllers\ConfigurationController::class, 'updateUser'])->name('update_user');
    //Eliminar usuario
    Route::get('/delete_user/{id}', [App\Http\Controllers\ConfigurationController::class, 'deleteUser'])->name('delete_user');

    //CALENDARIOS
    //Listar calendarios
    Route::post('/list_calendars', [App\Http\Controllers\ConfigurationController::class, 'listCalendars'])->name('list_calendars');
    //Información necesaria para el formulario de calendarios
    Route::get('/get_info_form_add_calendar', [App\Http\Controllers\ConfigurationController::class, 'getInfoFormCalendars'])->name('get_info_form_add_calendar');
    //Añadir calendario 
    Route::post('/add_calendar', [App\Http\Controllers\ConfigurationController::class, 'addCalendar'])->name('add_calendar');
    //Eliminar calendario
    Route::get('/delete_calendar/{id}', [App\Http\Controllers\ConfigurationController::class, 'deleteCalendar'])->name('delete_calendar');
    //Consultar información de un calendario
    Route::get('/get_info_calendar/{id}', [App\Http\Controllers\ConfigurationController::class, 'getInfoCalendar'])->name('get_info_calendar');
    //Actualizar calendario
    Route::post('/update_calendar', [App\Http\Controllers\ConfigurationController::class, 'updateCalendar'])->name('update_calendar');
    //Listar calendarios para exportar
    Route::post('/list_calendars_to_export', [App\Http\Controllers\ConfigurationController::class, 'listCalendarsToExport'])->name('list_calendars_to_export');
    //Descargar CSV de calendarios
    Route::get('/download_list_calendars_csv/{filter?}', [App\Http\Controllers\ConfigurationController::class, 'downloadListCalendarsCsv'])->name('download_list_calendars_csv');

    //ARTICULOS
    //Listar artículos
    Route::post('/list_articles', [App\Http\Controllers\ConfigurationController::class, 'listArticles'])->name('list_articles');
    //Listar para el select de areas
    Route::get('/get_areas', [App\Http\Controllers\ConfigurationController::class, 'getAreas'])->name('get_areas');
    //Listar para el select de sectores
    Route::get('/get_sectors/{id?}', [App\Http\Controllers\ConfigurationController::class, 'getSectors'])->name('get_sectors');
    //Listar para el select de marcas
    Route::get('/get_brands/{id}', [App\Http\Controllers\ConfigurationController::class, 'getBrands'])->name('get_brands');
    //Listar para el select de productos 
    Route::get('/get_products/{id}', [App\Http\Controllers\ConfigurationController::class, 'getProducts'])->name('get_products');
    //Listar para el select de artículos 
    Route::get('/get_articles/{id}', [App\Http\Controllers\ConfigurationController::class, 'getArticles'])->name('get_articles');
    //Añadir un artículo
    Route::post('/add_article', [App\Http\Controllers\ConfigurationController::class, 'addArticle'])->name('add_article');
    //Consultar información de un artículo
    Route::get('/get_info_article/{id}', [App\Http\Controllers\ConfigurationController::class, 'getInfoArticle'])->name('get_info_article');
    //Eliminar artículo
    Route::get('/delete_article/{id}', [App\Http\Controllers\ConfigurationController::class, 'deleteArticle'])->name('delete_article');
    //Actualizar artículo
    Route::post('/update_article', [App\Http\Controllers\ConfigurationController::class, 'updateArticle'])->name('update_article');
    //Actualizar exento de IVA de un artículo
    Route::post('/change_exempt', [App\Http\Controllers\ConfigurationController::class, 'changeExempt'])->name('change_exempt');
    //Listar calendarios para exportar
    Route::post('/list_articles_to_export', [App\Http\Controllers\ConfigurationController::class, 'listArticlesToExport'])->name('list_articles_to_export');
    //Descargar CSV de artículos
    Route::get('/download_list_articles_csv/{filter?}', [App\Http\Controllers\ConfigurationController::class, 'downloadListArticlesCsv'])->name('download_list_articles_csv');
    //END CONFIGURATION

    //Ruta para los pdfs
    Route::get('pdfs_bills/{slug}', [
        App\Http\Controllers\FileController::class, 'showPdf'
    ]);

    Route::get('/{vue_capture?}', function () {
        return view('layouts.back.admin');
    })->where('vue_capture', '[\/\w\.-]*');
});
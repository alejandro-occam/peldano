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
    

    Route::get('/{vue_capture?}', function () {
        return view('layouts.back.admin');
    })->where('vue_capture', '[\/\w\.-]*');
});
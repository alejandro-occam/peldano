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
    

    Route::get('/{vue_capture?}', function () {
        return view('layouts.back.admin');
    })->where('vue_capture', '[\/\w\.-]*');
});
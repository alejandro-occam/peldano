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

Route::group(['middleware' => ['auth', 'verified', 'admin'], 'prefix' => 'admin'], function () {
    Route::post('/list_user', [App\Http\Controllers\ConfigurationController::class, 'listUsers'])->name('list_user');

    Route::get('/{vue_capture?}', function () {
        return view('layouts.back.admin');
    })->where('vue_capture', '[\/\w\.-]*');
});

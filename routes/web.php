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

Route::get('/admin', function () {
    return redirect('/login');
})->name('login');

Auth::routes();

Route::group(['middleware' => ['auth', 'verified', 'admin']], function () {

    Route::get('/{vue_capture?}', function () {
        return view('layouts.app');
    })->where('vue_capture', '[\/\w\.-]*');
});

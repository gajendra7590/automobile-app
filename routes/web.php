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




Route::get('login','AuthController@loginGet')->name('loginGet');
Route::post('login','AuthController@loginPost')->name('loginPost');

Route::get('forgotPassword','AuthController@forgotPasswordGet')->name('forgotPasswordGet');
Route::post('forgotPassword','AuthController@forgotPasswordPost')->name('forgotPasswordPost');

Route::prefix('/')->middleware('auth')->group(function () {

    Route::get('dashboard','DashboardController@dashboardGet')->name('dashboardGet');


});

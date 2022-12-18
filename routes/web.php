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



Route::get('/', 'AuthController@index')->name('authIndex');
Route::get('login', 'AuthController@loginGet')->name('loginGet');
Route::post('login', 'AuthController@loginPost')->name('loginPost');

Route::get('forgotPassword', 'AuthController@forgotPasswordGet')->name('forgotPasswordGet');
Route::post('forgotPassword', 'AuthController@forgotPasswordPost')->name('forgotPasswordPost');

Route::prefix('/')->middleware('auth')->group(function () {
    Route::get('logout', 'AuthController@logout')->name('logout');
    Route::get('profile', 'AuthController@profile')->name('profile');
    Route::get('dashboard', 'DashboardController@dashboardIndex')->name('dashboardIndex');

    //Bike - Make/Model/Colors
    Route::resource('brands', 'BikeBrandsController');

    Route::resource('branches', 'BranchController');

    Route::resource('models', 'BikeModelController');

    Route::resource('colors', 'BikeColorController');

    Route::resource('dealers', 'BikeDealerController');

    //Address Module
    Route::resource('states', 'StateController');
    Route::resource('districts', 'DistrictController');
    Route::resource('cities', 'CityController');

    // Purchases
    Route::resource('purchases', 'BikePurchaseController');

    // Sales
    Route::resource('sales', 'SaleController');

    // RTO
    Route::resource('rto', 'RtoController');

    //Users Module
    // Agents
    Route::resource('agents', 'BikeAgentController');
    //Roles
    Route::resource('roles', 'RoleController');
    //Users
    Route::resource('users', 'UserController');
});

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

    Route::post('profileUpdate', 'AuthController@profileUpdate')->name('profileUpdate');
    Route::post('passwordUpdate', 'AuthController@passwordUpdate')->name('passwordUpdate');
    //Bike - Make/Model/Colors
    Route::resource('brands', 'BikeBrandsController');

    Route::resource('branches', 'BranchController');

    Route::resource('bankFinancers', 'BankFinancerController');

    Route::resource('models', 'BikeModelController');

    Route::resource('colors', 'BikeColorController');

    Route::resource('dealers', 'BikeDealerController');

    //Address Module
    Route::resource('states', 'StateController');
    Route::resource('districts', 'DistrictController');
    Route::resource('cities', 'CityController');
    Route::get('create/cities/bulk', 'CityController@createBulk')->name('city.create.popup');
    Route::get('store/cities/bulk', 'CityController@storeBulk')->name('city.create.bulk');

    //GST
    Route::resource('gst-rates', 'GstRateController');
    Route::resource('gst-rates-rto', 'GstRtoRateController');


    // Purchases
    Route::resource('purchases', 'PurchaseController');
    Route::get('getPurchaseDetails/{id}', 'PurchaseController@getPurchaseDetails')->name('getPurchaseDetails');
    Route::get('getModelsList/{id}', 'PurchaseController@getModelsList')->name('getModelsList');
    //getPurchaseModels

    //Quotations
    Route::resource('quotations', 'QuotationController');
    Route::get('getQuotationDetails/{id}', 'QuotationController@getQuotationDetails')->name('getQuotationDetails');
    Route::get('print-quotation/{id}', 'QuotationController@printQuotation')->name('print-quotation');
    //quot-print
    // Sales
    Route::resource('sales', 'SaleController');
    // RTO
    Route::resource('rto', 'RtoRegistrationController');

    //Users Module
    // Agents
    Route::resource('agents', 'BikeAgentController');
    //Roles
    Route::resource('roles', 'RoleController');
    //Users
    Route::resource('users', 'UserController');
    Route::get('changePassword/{user}', 'UserController@changePassword')->name('user.changePassword');
    Route::post('changePasswordPost/{user}', 'UserController@changePasswordPost')->name('user.changePassword.post');

    //AjaxCommonController
    Route::post('getAjaxDropdown', 'AjaxCommonController@index')->name('getAjaxDropdown');
});


// 404 / Except Above Route
Route::get('*', function () {
    return 'TEst';
})->name('404Route');

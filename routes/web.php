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

    Route::post('status/{id}', 'AjaxCommonController@status')->name('status');

    Route::get('logout', 'AuthController@logout')->name('logout');
    Route::get('profile', 'AuthController@profile')->name('profile');
    Route::get('dashboard', 'DashboardController@dashboardIndex')->name('dashboardIndex');

    Route::post('profileUpdate', 'AuthController@profileUpdate')->name('profileUpdate');
    Route::post('passwordUpdate', 'AuthController@passwordUpdate')->name('passwordUpdate');

    //Bike - Make/Model/Colors
    Route::resource('brands', 'BikeBrandsController');

    //Branches
    Route::resource('branches', 'BranchController');

    //Models
    Route::resource('models', 'BikeModelController');

    //Colors
    Route::resource('colors', 'BikeColorController');
    Route::get('getColorsList/{id}', 'BikeColorController@getColorsList')->name('getColorsList');

    //Bike financers
    Route::resource('bankFinancers', 'BankFinancerController');

    //Dealers
    Route::resource('dealers', 'BikeDealerController');

    //Address Module
    Route::resource('states', 'StateController');
    Route::resource('districts', 'DistrictController');
    Route::resource('cities', 'CityController');
    Route::get('create/cities/bulk', 'CityController@createBulk')->name('city.create.popup');
    Route::get('store/cities/bulk', 'CityController@storeBulk')->name('city.create.bulk');

    Route::get('plusAction', 'AjaxCommonController@plusAction')->name('plusAction');

    //GST
    Route::resource('gst-rates', 'GstRateController');

    //Rto Rates
    Route::resource('gst-rto-rates', 'GstRtoRateController');

    //RTO Agents
    Route::resource('rto-agents', 'RtoAgentController');

    //Tyre Brands
    Route::resource('tyreBrands', 'TyreBrandController');

    //Battery Brands
    Route::resource('batteryBrands', 'BatteryBrandController');

    //Purchases
    Route::resource('purchases', 'PurchaseController');
    Route::get('getPurchaseDetails/{id}', 'PurchaseController@getPurchaseDetails')->name('getPurchaseDetails');
    Route::get('getModelsList/{id}', 'PurchaseController@getModelsList')->name('getModelsList');

    Route::get('purchaseTransfer/{id}', 'PurchaseTransferController@transferIndex')->name('transferIndex');
    Route::post('purchaseTransfer/{id}', 'PurchaseTransferController@transferSave')->name('transferSave');

    Route::get('purchaseReturn/{id}', 'PurchaseTransferController@returnIndex')->name('returnIndex');
    Route::post('purchaseReturn/{id}', 'PurchaseTransferController@returnSave')->name('returnSave');

    //Invoices
    Route::resource('purchaseInvoices', 'PurchaseInvoicesController');

    //Transfers & Returns
    Route::resource('purchaseTransfers', 'PurchaseTransfersController');
    Route::get('getTransferPurchasesList', 'PurchaseTransfersController@getTransferPurchasesList')->name('getTransferPurchasesList');
    //purchaseTransferDeliveryChallan
    Route::get('purchaseTransferDeliveryChallan/{id}', 'PurchaseTransfersController@show')->name('purchaseTransferDeliveryChallan');


    //Quotations
    Route::resource('quotations', 'QuotationController');
    Route::get('getQuotationDetails/{id}', 'QuotationController@getQuotationDetails')->name('getQuotationDetails');
    Route::get('printQuotation/{id}', 'QuotationController@printQuotation')->name('printQuotation');
    Route::get('close-quotation/{id}', 'QuotationController@closeQuotation')->name('quotation.close');
    Route::post('close-quotation-post/{id}', 'QuotationController@closeQuotationPost')->name('quotationclosepost');

    // Sales
    Route::resource('sales', 'SaleController');
    Route::get('ajax-loade-view', 'SaleController@ajaxLoadeView')->name('ajaxLoadeView');
    Route::get('deliveryChallanFull/{id}', 'SaleController@deliveryChallanFull')->name('deliveryChallanFull');
    Route::get('deliveryChallanOnRoad/{id}', 'SaleController@deliveryChallanOnRoad')->name('deliveryChallanOnRoad');


    // SALES ACCOUNT
    Route::resource('saleAccounts', 'SalesAccountController');
    Route::get('sales-detail-modal', 'SalesAccountController@salesDetailModal')->name('salesDetailModal');
    Route::post('installmentPay', 'SalesAccountController@installmentPay')->name('installmentPay');
    Route::get('printPayemntReciept/{id}', 'SalesAccountController@printPayemntReciept')->name('printPayemntReciept');

    //Payment Tabs
    Route::get('getPaymentTabs/{id}', 'SalesAccountController@getPaymentTabs')->name('getPaymentTabs');

    // Sales Return By customer
    Route::resource('customerReturns', 'CustomerReturnsController');
    Route::get('select2DropdownByType', 'AjaxCommonController@select2DropdownByType')->name('select2DropdownByType');
    Route::get('showTransactions/{id}', 'CustomerReturnsController@showTransactions')->name('showTransactions');

    //Sales Refund
    Route::resource('customerRefunds', 'CustomerReturnSaleRefundController');

    //RTO Registration
    Route::get('rtoRegistration/ajaxChangeContent', 'RtoRegistrationController@ajaxChangeContent')->name('ajaxChangeContent');
    Route::resource('rtoRegistration', 'RtoRegistrationController');

    //RTO Agent PAyment
    Route::resource('rtoAgentPayments', 'RtoAgentPaymentHistoryController');

    //Brokers
    Route::resource('brokers', 'BrokerController');

    //Salesmans
    Route::resource('salesmans', 'SalesmanController');

    //Roles
    Route::resource('roles', 'RoleController');

    //Users
    Route::resource('users', 'UserController');
    Route::get('changePassword/{user}', 'UserController@changePassword')->name('user.changePassword');
    Route::post('changePasswordPost/{user}', 'UserController@changePasswordPost')->name('user.changePassword.post');

    //AjaxCommonController
    Route::post('getAjaxDropdown', 'AjaxCommonController@index')->name('getAjaxDropdown');

    //Reports
    Route::get('loadReportSection', 'ReportController@loadReportSection')->name('loadReportSection');
    Route::get('downloadReport', 'ReportController@downloadReport')->name('downloadReport');

    Route::resource('reports', 'ReportController');

    //Document Uploads
    Route::get('getSectionTypeDropdown', 'DocumentUploadController@getSectionTypeDropdown')->name('getSectionTypeDropdown');
    Route::resource('documentUploads', 'DocumentUploadController');

    Route::post('cityStore', 'PlusActionController@cityStore')->name('cityStore');
});


// 404 / Except Above Route
Route::get('*', function () {
    return 'TEst';
})->name('404Route');

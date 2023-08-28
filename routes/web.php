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

    //Model Variants
    Route::resource('modelVariants', 'BikeModelVariantController');

    //Variants Colors
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

    //paidFromBankAccounts
    Route::resource('paidFromBankAccounts', 'PaidFromBankAccountController');

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

    //SKU SALE PRICE
    Route::resource('skuSalesPrice', 'SkuSalePriceController');

    //Purchases
    Route::resource('purchases', 'PurchaseController');
    Route::get('getPurchaseDetails/{id}', 'PurchaseController@getPurchaseDetails')->name('getPurchaseDetails');
    Route::get('getModelsList/{id}', 'PurchaseController@getModelsList')->name('getModelsList');

    //Purchase Return To Dealers
    Route::get('purchaseReturnToDealers/backToStock/{id}', 'PurchaseReturnController@backToStock')->name('backToStock');
    Route::resource('purchaseReturnToDealers', 'PurchaseReturnController');

    //Invoices
    Route::resource('purchaseInvoices', 'PurchaseInvoicesController');

    //Transfers & Returns
    Route::resource('purchaseTransfers', 'PurchaseTransfersController');

    Route::get('purchaseTransferReturn/{id}', 'PurchaseTransfersController@returnIndex')->name('purchaseTransferReturnIndex');
    Route::post('purchaseTransferReturn/{id}', 'PurchaseTransfersController@returnSave')->name('purchaseTransferReturnSave');

    Route::get('getTransferPurchasesList', 'PurchaseTransfersController@getTransferPurchasesList')->name('getTransferPurchasesList');

    //purchaseTransferDeliveryChallan
    Route::get('purchaseTransferDeliveryChallan/{id}', 'PurchaseTransfersController@show')->name('purchaseTransferDeliveryChallan');

    //Purchase Dealer Payments
    Route::resource('purchaseDealerPayments', 'PurchaseDealerPaymentHistoryController');
    Route::get('purchaseDealerPaymentLedger/{id}', 'PurchaseDealerPaymentHistoryController@downloadLedger')->name('purchaseDealerPaymentLedger');

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
    Route::get('deliveryTaxChallan/{id}', 'SaleController@deliveryTaxChallan')->name('deliveryTaxChallan');
    Route::get('deliveryChallanWithTxn/{id}', 'SaleController@deliveryChallanWithTxn')->name('deliveryChallanWithTxn');


    // SALES ACCOUNT
    Route::resource('saleAccounts', 'SalesAccountController');
    Route::get('sales-detail-modal', 'SalesAccountController@salesDetailModal')->name('salesDetailModal');
    Route::post('installmentPay', 'SalesAccountController@installmentPay')->name('installmentPay');
    Route::get('printPayemntReciept/{id}', 'SalesAccountController@printPayemntReciept')->name('printPayemntReciept');

    //Cash Payment
    Route::resource('salesCash', 'SalePaymentCashController');
    Route::get('addChargesIndex/{id}', 'SalePaymentCashController@addChargesIndex')->name('addChargesIndex');
    Route::post('addChargesSave/{id}', 'SalePaymentCashController@addChargesSave')->name('addChargesSave');
    Route::get('salesCashReceipt/{id}', 'SalePaymentCashController@salesCashReceipt')->name('salesCashReceipt');

    //Bank Finance Payment
    Route::resource('salesBankFinanace', 'SalePaymentBankFinanaceController');
    //CREATE
    Route::get('bankFinanacePay/{id}', 'SalePaymentBankFinanaceController@payIndex')->name('bankFinanacePayIndex');
    Route::post('bankFinanacePay/{id}', 'SalePaymentBankFinanaceController@payStore')->name('bankFinanacePayStore');
    //UPDATE
    Route::get('bankFinanacePayEdit/{id}', 'SalePaymentBankFinanaceController@payEdit')->name('bankFinanacePayEdit');
    Route::put('bankFinanacePayUpdate/{id}', 'SalePaymentBankFinanaceController@payUpdate')->name('bankFinanacePayUpdate');
    //CANCEL
    Route::get('bankFinanaceCancel/{id}', 'SalePaymentBankFinanaceController@bankFinanaceCancel')->name('bankFinanaceCancel');

    //Personal Finance Payment
    Route::resource('salesPersonalFinanace', 'SalePaymentPersonalFinanaceController');
    Route::get('personalFinanacePay/{id}', 'SalePaymentPersonalFinanaceController@payIndex')->name('personalFinanacePayIndex');
    Route::post('personalFinanacePay/{id}', 'SalePaymentPersonalFinanaceController@payStore')->name('personalFinanacePayStore');
    Route::get('printReceipt/{id}', 'SalePaymentPersonalFinanaceController@printReceipt')->name('printReceiptPF');
    Route::get('cancelPf/{id}', 'SalePaymentPersonalFinanaceController@cancel')->name('cancelPf');
    //cancel

    //Transactions
    Route::get('transactions/{id}', 'SalePaymentTransactionController@show')->name('transactions.show');

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

    //Update Non Editable Detail
    Route::resource('updateNonEditableDetail', 'UpdateNonEditableInfoController');
    Route::get('getDocumentTypeAjaxSelect2Data', 'UpdateNonEditableInfoController@getDocumentTypeData')->name('getDocumentTypeAjaxSelect2Data');

    //bulk upload purchases
    Route::resource('bulkUploadPurchases', 'BulkUploadPurchaseController');

    //Plus Button City Create
    Route::post('cityStore', 'PlusActionController@cityStore')->name('cityStore');
    Route::get('openFinanceDetail', 'PlusActionController@openFinanceDetail')->name('openFinanceDetail');
});


// 404 / Except Above Route
Route::get('*', function () {
    return 'TEst';
})->name('404Route');

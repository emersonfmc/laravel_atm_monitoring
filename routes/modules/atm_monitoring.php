<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ATM\ClientContoller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ATM\DefaultController;
use App\Http\Controllers\ATM\AtmHeadOfficeController;
use App\Http\Controllers\ATM\AtmTransactionController;
use App\Http\Controllers\ATM\AtmBranchOfficeController;
use App\Http\Controllers\ATM\PassbookCollectionController;
use App\Http\Controllers\ATM\PassbookCollectionTransactionController;

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

//Update User Details
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::controller(ClientContoller::class)->group(function () {
        Route::get('/clients/page', 'client_page')->name('clients.page');
        Route::get('/clients/data', 'client_data')->name('clients.data');
        Route::get('/clients/get/{id}', 'clientGet')->name('clients.get');
        Route::post('/clients/create', 'clientCreate')->name('clients.create');
        Route::post('/clients/update', 'clientUpdate')->name('clients.update');
        Route::post('/pension_number/validate', 'PensionNumberValidate')->name('pension.number.validate');
    });
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::controller(AtmHeadOfficeController::class)->group(function () {
        Route::get('/HeadOfficePage', 'HeadOfficePage')->name('HeadOfficePage');
        Route::get('/HeadOfficeData', 'HeadOfficeData')->name('HeadOfficeData');
        Route::get('/SafekeepPage', 'SafekeepPage')->name('SafekeepPage');
        Route::get('/SafekeepData', 'SafekeepData')->name('SafekeepData');
        Route::get('/ReleasedPage', 'ReleasedPage')->name('ReleasedPage');
        Route::get('/ReleasedData', 'ReleasedData')->name('ReleasedData');
        Route::post('/PassbookForCollectionSetup', 'PassbookForCollectionSetup')->name('PassbookForCollectionSetup');
    });
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::controller(AtmBranchOfficeController::class)->group(function () {
        Route::get('/BranchOfficePage', 'BranchOfficePage')->name('BranchOfficePage');
        Route::get('/BranchOfficeData', 'BranchOfficeData')->name('BranchOfficeData');
        Route::get('/CancelledLoanPage', 'CancelledLoanPage')->name('CancelledLoanPage');
        Route::get('/CancelledLoanData', 'CancelledLoanData')->name('CancelledLoanData');
    });
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::controller(AtmTransactionController::class)->group(function () {
        Route::get('/TransactionPage', 'TransactionPage')->name('TransactionPage');
        Route::get('/TransactionData', 'TransactionData')->name('TransactionData');
        Route::get('/TransactionGet', 'TransactionGet')->name('TransactionGet');
        Route::post('/TransactionCreate', 'TransactionCreate')->name('TransactionCreate');
        Route::post('/TransactionAddAtm', 'TransactionAddAtm')->name('TransactionAddAtm');
        Route::post('/TransactionTransferBranch', 'TransactionTransferBranch')->name('TransactionTransferBranch');
        Route::post('/TransactionReplacementCreate', 'TransactionReplacementCreate')->name('TransactionReplacementCreate');
        Route::post('/TransactionReleaseCreate', 'TransactionReleaseCreate')->name('TransactionReleaseCreate');
        Route::post('/TransactionEditClient', 'TransactionEditClient')->name('TransactionEditClient');
        Route::post('/TransactionUpdate', 'TransactionUpdate')->name('TransactionUpdate');

        Route::get('/TransactionReceivingPage', 'TransactionReceivingPage')->name('TransactionReceivingPage');
        Route::get('/TransactionReceivingData', 'TransactionReceivingData')->name('TransactionReceivingData');
        Route::get('/TransactionReleasingPage', 'TransactionReleasingPage')->name('TransactionReleasingPage');
        Route::get('/TransactionReleasingData', 'TransactionReleasingData')->name('TransactionReleasingData');
    });
});


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::controller(PassbookCollectionController::class)->group(function () {
        Route::get('/PassbookCollectionSetUpPage', 'PassbookCollectionSetUpPage')->name('PassbookCollectionSetUpPage');
        Route::get('/PassbookCollectionData', 'PassbookCollectionData')->name('PassbookCollectionData');
        Route::post('/PassbookForCollectionCreate', 'PassbookForCollectionCreate')->name('PassbookForCollectionCreate');
        Route::get('/PassbookCollectionAllTransactionPage', 'PassbookCollectionAllTransactionPage')->name('PassbookCollectionAllTransactionPage');
        Route::get('/PassbookCollectionAllTransactionData', 'PassbookCollectionAllTransactionData')->name('PassbookCollectionAllTransactionData');
        Route::get('/PassbookCollectionAllTransactionGet', 'PassbookCollectionAllTransactionGet')->name('PassbookCollectionAllTransactionGet');
        Route::get('/PassbookCollectionTransactionPage', 'PassbookCollectionTransactionPage')->name('PassbookCollectionTransactionPage');
        Route::get('/PassbookCollectionTransactionData', 'PassbookCollectionTransactionData')->name('PassbookCollectionTransactionData');
        Route::get('/PassbookCollectionTransactionGet', 'PassbookCollectionTransactionGet')->name('PassbookCollectionTransactionGet');
        Route::post('/PassbookCollectionTransactionUpdate', 'PassbookCollectionTransactionUpdate')->name('PassbookCollectionTransactionUpdate');
        Route::post('/PassbookCollectionTransactionCancelled', 'PassbookCollectionTransactionCancelled')->name('PassbookCollectionTransactionCancelled');

        Route::get('/PassbookCollectionReceivingPage', 'PassbookCollectionReceivingPage')->name('PassbookCollectionReceivingPage');
        Route::get('/PassbookCollectionReleasingPage', 'PassbookCollectionReleasingPage')->name('PassbookCollectionReleasingPage');
        Route::get('/PassbookCollectionReturningPage', 'PassbookCollectionReturningPage')->name('PassbookCollectionReturningPage');
    });
});


require __DIR__ . '/modules/settings.php';
require __DIR__ . '/modules/atm_monitoring.php';
require __DIR__ . '/modules/documents.php';





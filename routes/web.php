<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ATM\DefaultController;
use App\Http\Controllers\SystemController;

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

//Update User Details
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

// Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/main_dashboard', 'main_dashboard')->name('main_dashboard');

        Route::get('/elog_monitoring_dashboard', 'elog_monitoring_dashboard')->name('elog_monitoring_dashboard');
        Route::get('/elog_monitoring_dashboard_data', 'elog_monitoring_dashboard_data')->name('elog_monitoring_dashboard_data');
        Route::get('/elog_monitoring_transaction_data', 'elog_monitoring_transaction_data')->name('elog_monitoring_transaction_data');
        Route::get('/SidebarCount', 'SidebarCount')->name('SidebarCount');
    });
});

Route::controller(DefaultController::class)->group(function () {
    Route::get('/pension/types/fetch', 'PensionTypesFetch')->name('pension.types.fetch');
    Route::get('/AtmClientFetch', 'AtmClientFetch')->name('AtmClientFetch');
    Route::get('/AtmClientBanksFetch', 'AtmClientBanksFetch')->name('AtmClientBanksFetch');
    Route::get('/UserSelect', 'UserSelect')->name('UserSelect');
    Route::get('/UserSelectServerSide', 'UserSelectServerSide')->name('UserSelectServerSide');
    Route::get('/GenerateQRCode/{print_number}/{transaction_number}', 'GenerateQRCode')->name('generate_qr_code');
});



Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/users/page', 'users_page')->name('users.page');
        Route::get('/users/data', 'users_data')->name('users.data');
        Route::get('/users/get/{id}', 'users_get')->name('users.get');
        Route::post('/users/create', 'users_create')->name('users.create');
        Route::post('/users/update', 'users_update')->name('users.update');
        Route::get('/users/profile/{employee_id}', 'users_profile')->name('users.profile');
        Route::post('/users/profile/update/{employee_id}', 'users_profile_update')->name('users.profile.update');
    });
});



require __DIR__ . '/modules/atm_monitoring.php';
require __DIR__ . '/modules/settings.php';
require __DIR__ . '/modules/documents.php';
require __DIR__ . '/modules/system.php';






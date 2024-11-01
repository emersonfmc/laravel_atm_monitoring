<?php

use App\Http\Controllers\ATM\AtmBranchOfficeController;
use App\Http\Controllers\ATM\AtmHeadOfficeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ATM\ClientContoller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ATM\DefaultController;

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

//Update User Details
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

// Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

Route::controller(DefaultController::class)->group(function () {
    Route::get('/pension/types/fetch', 'PensionTypesFetch')->name('pension.types.fetch');
});


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/users/page', 'users_page')->name('users.page');
        Route::get('/users/data', 'users_data')->name('users.data');
        Route::get('/users/get/{id}', 'users_get')->name('users.get');
        Route::post('/users/create', 'users_create')->name('users.create');
        Route::post('/users/update', 'users_update')->name('users.update');
    });
});

Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'settings'], function () {
    Route::controller(SettingsController::class)->group(function () {
        Route::get('/districts', 'districts_page')->name('settings.district.page');
        Route::get('/districts/data', 'districts_data')->name('settings.district.data');
        Route::get('/districts/get/{id}', 'districtsGet')->name('settings.districts.get');
        Route::post('/districts/create', 'districtsCreate')->name('settings.districts.create');
        Route::post('/districts/update', 'districtsUpdate')->name('settings.districts.update');

        Route::get('/users_group_page', 'users_group_page')->name('settings.users.group.page');
        Route::get('/users_group_data', 'users_group_data')->name('settings.users.group.data');
        Route::get('/users/group/get/{id}', 'users_group_get')->name('settings.users.group.get');
        Route::post('/users_group_create', 'users_group_create')->name('settings.users.group.create');
        Route::post('/users_group_update', 'users_group_update')->name('settings.users.group.update');

        Route::get('/area_page', 'area_page')->name('settings.area.page');
        Route::get('/area_data', 'area_data')->name('settings.area.data');
        Route::get('/area/get/{id}', 'areaGet')->name('settings.area.get');
        Route::post('/area_create', 'areaCreate')->name('settings.area.create');
        Route::post('/area_update', 'areaUpdate')->name('settings.area.update');

        Route::get('/branch_page', 'branch_page')->name('settings.branch.page');
        Route::get('/branch_data', 'branch_data')->name('settings.branch.data');
        Route::get('/branch/get/{id}', 'branchGet')->name('settings.branch.get');
        Route::post('/branch/create', 'branchCreate')->name('settings.branch.create');
        Route::post('/branch/update', 'branchUpdate')->name('settings.branch.update');

        Route::get('/bank_page', 'bank_page')->name('settings.bank.page');
        Route::get('/bank_data', 'bank_data')->name('settings.bank.data');
        Route::get('/bank/get/{id}', 'bankGet')->name('settings.bank.get');
        Route::post('/bank/create', 'bankCreate')->name('settings.bank.create');
        Route::post('/bank/update', 'bankUpdate')->name('settings.bank.update');

        Route::get('/pension_types/page', 'pension_types_page')->name('settings.pension.types.page');
        Route::get('/pension_types/data', 'pension_types_data')->name('settings.pension.types.data');
        Route::get('/pension/types/get/{id}', 'pension_typesGet')->name('settings.pension.types.get');
        Route::post('/pension_types/create', 'pension_typesCreate')->name('settings.pension.types.create');
        Route::post('/pension_types/update', 'pension_typesUpdate')->name('settings.pension.types.update');

        Route::get('/transaction/action/page', 'transaction_action_page')->name('settings.transaction.action.page');
        Route::get('/transaction/action/data', 'transaction_action_data')->name('settings.transaction.action.data');
        Route::get('/transaction/action/get/{id}', 'transaction_typesGet')->name('settings.transaction.action.get');
        Route::post('/transaction/action/create', 'transaction_typesCreate')->name('settings.transaction.action.create');
        Route::post('/transaction/action/update', 'transaction_typesUpdate')->name('settings.transaction.action.update');

        Route::get('/area/using/district', 'areaGetBydistrict')->name('settings.area.using.district');
        Route::get('/branch/using/area', 'branchGetByarea')->name('settings.branch.using.area');

        Route::get('/release_reason/page', 'release_reason_page')->name('settings.release.reason.page');
        Route::get('/release_reason/data', 'release_reason_data')->name('settings.release.reason.data');
        Route::get('/release/reason/get/{id}', 'release_reason_get')->name('settings.release.reason.get');
        Route::post('/release/reason/create', 'release_reason_create')->name('settings.release.reason.create');
        Route::post('/release/reason/update', 'release_reason_update')->name('settings.release.reason.update');

        Route::get('/login_get_test', 'login_page')->name('login.page.test');
    });
});

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
    });
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::controller(AtmHeadOfficeController::class)->group(function () {
        Route::get('/BranchOfficePage', 'BranchOfficePage')->name('BranchOfficePage');
        Route::get('/BranchOfficeData', 'BranchOfficeData')->name('BranchOfficeData');
    });
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::controller(AtmBranchOfficeController::class)->group(function () {
        Route::get('/BranchOfficePage', 'BranchOfficePage')->name('BranchOfficePage');
        Route::get('/BranchOfficeData', 'BranchOfficeData')->name('BranchOfficeData');
    });
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/index', 'dashboard')->name('dashboard');
        // Route::resource('products', ProductController::class);
    });
});

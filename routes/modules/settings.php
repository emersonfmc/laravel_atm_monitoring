<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingsController;

Auth::routes();

Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'settings'], function () {
    Route::controller(SettingsController::class)->group(function () {
        Route::get('/settings_dashboard', 'settings_dashboard')->name('settings.dashboard.page');

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

        Route::get('/collection_date/page', 'collection_date_page')->name('settings.collection.date.page');
        Route::get('/collection_date/data', 'collection_date_data')->name('settings.collection.date.data');
        Route::get('/collection/date/get/{id}', 'collection_date_get')->name('settings.collection.date.get');
        Route::post('/collection/date/create', 'collection_date_create')->name('settings.collection.date.create');
        Route::post('/collection/date/update', 'collection_date_update')->name('settings.collection.date.update');

        Route::get('/maintenance/page', 'maintenance_page')->name('settings.maintenance.page');
        Route::get('/maintenance/data', 'maintenance_data')->name('settings.maintenance.data');
        Route::get('/maintenance/get/{id}', 'maintenance_get')->name('settings.maintenance.get');
        Route::post('/maintenance/create', 'maintenance_create')->name('settings.maintenance.create');
        Route::post('/maintenance/update', 'maintenance_update')->name('settings.maintenance.update');


        Route::get('/login_get_test', 'login_page')->name('login.page.test');
    });
});







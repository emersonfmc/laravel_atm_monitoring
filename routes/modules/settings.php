<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Settings\SettingsEFMainController;

Auth::routes();

Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'settings'], function () {
    Route::controller(SettingsController::class)->group(function () {
        Route::get('/settings_dashboard', 'settings_dashboard')->name('settings.dashboard.page');
        Route::get('/settings/monitoring/dashboard/data', 'settings_monitoring_dashboard_data')->name('settings.monitoring.dashboard.data');

        Route::get('system/logs/page', 'settings_system_logs_page')->name('settings.system.logs.page');
        Route::get('system/logs/data', 'settings_system_logs_data')->name('settings.system.logs.data');

        Route::get('/maintenance/page', 'maintenance_page')->name('settings.maintenance.page');
        Route::get('/maintenance/data', 'maintenance_data')->name('settings.maintenance.data');
        Route::get('/maintenance/get/{id}', 'maintenance_get')->name('settings.maintenance.get');
        Route::post('/maintenance/create', 'maintenance_create')->name('settings.maintenance.create');
        Route::post('/maintenance/update', 'maintenance_update')->name('settings.maintenance.update');
    });

});







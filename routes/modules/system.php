<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemController;

Auth::routes();

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::controller(SystemController::class)->group(function () {
        Route::post('/system/pin-code/logs', 'system_logs_create_pin_code')->name('system.pin-code.logs');

        Route::get('/system/announcement/page', 'system_annoucement_pages')->name('system.announcement.pages');
        Route::get('/system/announcement/data', 'system_annoucement_data')->name('system.announcement.data');
        Route::get('/system/announcement/display', 'system_annoucement_display')->name('system.announcement.display');

        Route::get('/system/announcement/get/{id}', 'system_annoucement_get')->name('system.announcement.get');
        Route::get('/system/announcement/specific/{id}', 'system_annoucement_specific')->name('system.announcement.specific');

        Route::get('/system/announcement/fetch', 'system_annoucement_fetch')->name('system.announcement.fetch');
        Route::get('/system/announcement/fetch/data', 'system_annoucement_fetch_data')->name('system.announcement.fetch.data');

        Route::get('/system/announcement/counts', 'system_annoucement_counts')->name('system.announcement.counts');
        Route::post('/system/announcement/create', 'system_annoucement_create')->name('system.announcement.create');
        Route::post('/system/announcement/update', 'system_annoucement_update')->name('system.announcement.update');

        // Route::get('/system/logs/page', 'system_logs_pages')->name('system.logs.pages');
        // Route::get('/system/logs/data', 'system_logs_data')->name('system.logs.data');
    });
});






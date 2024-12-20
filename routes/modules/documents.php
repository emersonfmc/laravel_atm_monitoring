<?php

use App\Http\Controllers\Documents\DocumentsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'settings'], function () {
    Route::controller(DocumentsController::class)->group(function () {
        Route::get('/documents_dashboard', 'documents_dashboard')->name('documents.dashboard.page');


    });
});







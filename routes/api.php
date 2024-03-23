<?php

// phpcs:disable Generic.Files.LineLength.TooLong

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home.index');

Route::post('session', [\App\Http\Controllers\Users\SessionsController::class, 'login'])->name('session.login');

Route::middleware('auth')->group(function () {
    Route::apiResource('document_types', \App\Http\Controllers\DocumentTypesController::class);

    Route::apiResource('persons', \App\Http\Controllers\PersonsController::class);
    Route::apiResource('users', \App\Http\Controllers\Users\UsersController::class);

    Route::apiResource('ubigeo/departments', \App\Http\Controllers\Ubigeo\DepartmentsController::class);
    Route::apiResource('ubigeo/provinces', \App\Http\Controllers\Ubigeo\ProvincesController::class);
    Route::apiResource('ubigeo/districts', \App\Http\Controllers\Ubigeo\DistrictsController::class);

    Route::get('session', [\App\Http\Controllers\Users\SessionsController::class, 'profile'])->name('session.profile');
    Route::delete('session', [\App\Http\Controllers\Users\SessionsController::class, 'logout'])->name('session.logout');
});

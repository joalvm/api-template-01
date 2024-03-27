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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home.index');

Route::post('session', [App\Http\Controllers\Users\SessionsController::class, 'login'])->name('session.login');

Route::middleware('auth')->group(function () {
    Route::apiResource('document_types', App\Http\Controllers\DocumentTypesController::class)->except(['index', 'show'])->whereNumber('document_type')->names('document_types');

    Route::apiResource('ubigeo/departments', App\Http\Controllers\Ubigeo\DepartmentsController::class)->except(['index', 'show'])->whereNumber('department')->names('ubigeo.departments');
    Route::apiResource('ubigeo/provinces', App\Http\Controllers\Ubigeo\ProvincesController::class)->except(['index', 'show'])->whereNumber('province')->names('ubigeo.provinces');
    Route::apiResource('ubigeo/districts', App\Http\Controllers\Ubigeo\DistrictsController::class)->except(['index', 'show'])->whereNumber('district')->names('ubigeo.districts');

    Route::apiResource('persons', App\Http\Controllers\PersonsController::class);
    Route::apiResource('users', App\Http\Controllers\Users\UsersController::class);

    Route::get('session', [App\Http\Controllers\Users\SessionsController::class, 'profile'])->name('session.profile');
    Route::delete('session', [App\Http\Controllers\Users\SessionsController::class, 'logout'])->name('session.logout');
});

Route::get('document_types', [App\Http\Controllers\DocumentTypesController::class, 'index'])->name('document_types.index');
Route::get('document_types/{document_type}', [App\Http\Controllers\DocumentTypesController::class, 'show'])->whereNumber('document_type')->name('document_types.show');

Route::get('ubigeo/departments', [App\Http\Controllers\Ubigeo\DepartmentsController::class, 'index'])->name('ubigeo.departments.index');
Route::get('ubigeo/departments/{department}', [App\Http\Controllers\Ubigeo\DepartmentsController::class, 'show'])->whereNumber('department')->name('ubigeo.departments.show');
Route::get('ubigeo/provinces', [App\Http\Controllers\Ubigeo\ProvincesController::class, 'index'])->name('ubigeo.provinces.index');
Route::get('ubigeo/provinces/{province}', [App\Http\Controllers\Ubigeo\ProvincesController::class, 'show'])->whereNumber('province')->name('ubigeo.provinces.show');
Route::get('ubigeo/districts', [App\Http\Controllers\Ubigeo\DistrictsController::class, 'index'])->name('ubigeo.districts.index');
Route::get('ubigeo/districts/{district}', [App\Http\Controllers\Ubigeo\DistrictsController::class, 'show'])->whereNumber('district')->name('ubigeo.districts.show');

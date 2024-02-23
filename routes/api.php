<?php

use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\ApiController;
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

Route::get('/', [ApiController::class, 'index'])->name('api.welcome');

Route::prefix('admins')->as('admin.')->group(function () {
    Route::post('register', [RegisterController::class, 'register'])->name('register');
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('password.forgot');
    Route::post('reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.reset');
});

Route::prefix('employees')->as('employee')->middleware('auth.admin')->group(function () {
    Route::get('', [EmployeeController::class, 'fetchAll'])->name('all');
    Route::post('', [EmployeeController::class, 'save'])->name('store');
    Route::get('{id}', [EmployeeController::class, 'fetchOne'])->name('one.by.id');
    Route::match(['put', 'patch'], '{id}', [EmployeeController::class, 'update'])->name('update');
    Route::delete('{id}', [EmployeeController::class, 'delete'])->name('delete');

    Route::group(['prefix' => '{id}/attendance', 'as' => 'attendance.'], function () {
        Route::post('arrival', [AttendanceController::class, 'recordArrival'])->name('arrival');
        Route::post('departure', [AttendanceController::class, 'recordDeparture'])->name('departure');
    });
});

Route::prefix('attendance')->as('attendance')->group(function () {
    Route::group(['prefix' => 'report', 'as' => 'report.'], function () {
        Route::get('excel', [AttendanceController::class, 'downloadAttendanceExcelReport'])->name('excel');
        Route::get('pdf', [AttendanceController::class, 'downloadAttendancePDFReport'])->name('pdf');
    });
});

Route::fallback(function () {
    return response()->json([
        'status' => 404,
        'message' => 'Route does not exists'
    ], 404);
});

<?php

use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
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

Route::get('/', fn () => response()->json([
    'status' => 200,
    'message' => 'Welcome to our Employee Management System API'
]));

Route::prefix('admins')->as('admin.')->group(function () {
    Route::post('register', [RegisterController::class, 'register'])->name('register');
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('password.forgot');
    Route::post('reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.reset');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('employees')->as('employee')->group(function () {
        Route::get('', [EmployeeController::class, 'fetchAll'])->name('all');
        Route::post('', [EmployeeController::class, 'save'])->name('store');
        Route::get('{id}', [EmployeeController::class, 'fetchOne'])->name('one.by.id');
        Route::patch('{id}', [EmployeeController::class, 'update'])->name('update');
        Route::delete('{id}', [EmployeeController::class, 'delete'])->name('delete');

        Route::post('{id}/record-arrival', [AttendanceController::class, 'recordArrival'])->name('attendance.arrival');
        Route::post('{id}/record-departure', [AttendanceController::class, 'recordDeparture'])->name('attendance.departure');
    });
});

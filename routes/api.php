<?php

use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\AttendanceController;
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

Route::prefix('admins')->group(function() {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [LoginController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::prefix('employees')->group(function() {
        Route::get('', [EmployeeController::class, 'fetchAll']);
        Route::post('', [EmployeeController::class, 'save']);
        Route::get('{id}', [EmployeeController::class, 'fetchOne']);
        Route::patch('{id}', [EmployeeController::class, 'update']);
        Route::delete('{id}', [EmployeeController::class, 'delete']);

        Route::post('{id}/record-arrival', [AttendanceController::class, 'recordArrival']);
        Route::post('{id}/record-departure', [AttendanceController::class, 'recordDeparture']);
    });

});

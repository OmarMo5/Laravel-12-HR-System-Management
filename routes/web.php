<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AttendendanceController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HRController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

// Language Route
/* Route::get('lang/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'ar'])) {
        session()->put('locale', $lang);
    }
    return redirect()->back();
})->name('change.lang'); */
Route::get('lang/{lang}', [App\Http\Controllers\HRController::class, 'changeLang'])->name('change.lang');


Route::group(['middleware' => 'auth'], function () {
    Route::get('home', function () {
        return view('dashboard.home');
    });
});

Auth::routes();

Route::group(['namespace' => 'App\Http\Controllers\Auth'], function () {
    // -----------------------------login----------------------------------------//
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'authenticate');
        Route::get('/logout', 'logout')->name('logout');
        Route::get('logout/page', 'logoutPage')->name('logout/page');
    });

    // ------------------------------ register ----------------------------------//
    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'register')->name('register');
        Route::post('/register', 'storeUser')->name('register');
    });

    // ----------------------------- forget password ----------------------------//
    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::get('forget-password', 'getEmail')->name('forget-password');
        Route::post('forget-password', 'postEmail')->name('forget-password');
    });

    // ----------------------------- reset password -----------------------------//
    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get('reset-password/{token}', 'getPassword');
        Route::post('reset-password', 'updatePassword');
    });
});

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    // -------------------------- main dashboard ----------------------//
    Route::controller(HomeController::class)->group(function () {
        Route::get('/home', 'index')->middleware('auth')->name('home');
    });

    // -------------------------- pages ----------------------//
    Route::controller(AccountController::class)->group(function () {
        Route::get('page/account/{user_id}', 'profileDetail')->middleware('auth');
    });

    // -------------------------- hr ----------------------//
    Route::middleware('auth')->prefix('hr/')->group(function () {
        Route::controller(AttendendanceController::class)->group(function () {
            Route::get('employee/attendance/dashboard', 'employeeAttendanceDashboard')->name('employee/attendance/dashboard');
            Route::post('employee/attendance/check-in', 'employeeCheckIn')->name('employee/attendance/check-in');
            Route::post('employee/attendance/check-out', 'employeeCheckOut')->name('employee/attendance/check-out');
            Route::get('employee/attendance/history', 'employeeAttendanceHistory')->name('employee/attendance/history');
        });

        Route::controller(HRController::class)->group(function () {
            // Employee routes
            Route::get('employee/list', 'employeeList')->name('hr/employee/list');
            Route::post('employee/save', 'employeeSaveRecord')->name('hr/employee/save');
            Route::post('employee/update', 'employeeUpdateRecord')->name('hr/employee/update');
            Route::post('employee/delete', 'employeeDeleteRecord')->name('hr/employee/delete');


            // Holiday routes
            Route::get('holidays/page', 'holidayPage')->name('hr/holidays/page');
            Route::post('holidays/save', 'holidaySaveRecord')->name('hr/holidays/save');
            Route::post('holidays/delete', 'holidayDeleteRecord')->name('hr/holidays/delete');

            // Leave routes
            Route::get('leave/employee/page', 'leaveEmployee')->name('hr/leave/employee/page');
            Route::get('create/leave/employee/page', 'createLeaveEmployee')->name('hr/create/leave/employee/page');
            Route::post('create/leave/employee/save', 'saveRecordLeave')->name('hr/create/leave/employee/save');
            Route::get('view/detail/leave/{id}', 'viewDetailLeave')->name('hr/view/detail/leave');

            // HR Leave routes
            Route::get('leave/hr/page', 'leaveHR')->name('hr/leave/hr/page');
            Route::get('create/leave/hr/page', 'createLeaveHR')->name('hr/create/leave/hr/page');
            Route::post('create/leave/hr/save', 'saveRecordLeaveHR')->name('hr/create/leave/hr/save');

            // AJAX routes
            Route::post('get/information/leave', 'getInformationLeave')->name('hr/get/information/leave');
            Route::post('get/employee/leave/info', 'getEmployeeLeaveInfo')->name('hr/get/employee/leave/info');
            Route::post('update/leave/status', 'updateLeaveStatus')->name('hr/update/leave/status');
            Route::post('delete/leave', 'deleteLeave')->name('hr/delete/leave');

            // Attendance routes
            /* Route::get('attendance/page', 'attendance')->name('hr/attendance/page');*/
            Route::get('attendance/main/page', 'attendanceMain')->name('hr/attendance/main/page');

            Route::get('attendance/page', 'attendance')->name('hr/attendance/page');
            Route::post('attendance/check-in', 'checkIn')->name('hr/attendance/check-in');
            Route::post('attendance/check-out', 'checkOut')->name('hr/attendance/check-out');
            Route::get('attendance/get-employee-data/{userId}', 'getEmployeeAttendanceData')->name('hr/attendance/get-employee-data');
            Route::get('attendance/get-details/{id}', 'getAttendanceDetails')->name('hr/attendance/get-details');
            Route::post('attendance/update-status', 'updateAttendanceStatus')->name('hr/attendance/update-status');
            Route::post('attendance/bulk-approve', 'bulkApproveAttendance')->name('hr/attendance/bulk-approve');
            Route::post('attendance/bulk-reject', 'bulkRejectAttendance')->name('hr/attendance/bulk-reject');

            // Department routes
            Route::get('department/page', 'department')->name('hr/department/page');
            Route::post('department/save', 'saveRecordDepartment')->name('hr/department/save');
            Route::post('department/delete', 'deleteRecordDepartment')->name('hr/department/delete');
        });
    });
});

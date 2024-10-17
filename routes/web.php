<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin as Admin;
use App\Http\Controllers\UserParent as UserParent;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\KatalogController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::controller(FrontendController::class)->group(function() {
    Route::get('/','index');
    Route::post('/send-attendance', 'sendAttendance');
});

Route::controller(Admin\AuthController::class)->group(function() {
    Route::get('/admin/login','index')->name('admin.login');
    Route::post('/admin/sign-in', 'authenticate');
    Route::post('/admin/logout', 'logout');
});

Route::controller(UserParent\AuthController::class)->group(function() {
    Route::get('/parent/login', 'index')->name('parent.login');
    Route::post('/parent/sign-in', 'authenticate');
    Route::post('/parent/logout', 'logout');
});

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('admin.index');
    Route::get('dashboard', [Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('settings', Admin\SettingController::class);
    Route::put('settings/update-identity', [Admin\SettingController::class, 'update_identity']);

    Route::resource('users', Admin\UserController::class);
    Route::resource('users/roles', Admin\RoleController::class);
    Route::post('users/resetpassword', [Admin\UserController::class, 'resetpassword']);

    Route::get('holidays', [Admin\HolidayController::class, 'index']);
    Route::post('holidays', [Admin\HolidayController::class, 'store']);
    Route::post('holidays/delete', [Admin\HolidayController::class, 'delete']);

    Route::get('students', [Admin\StudentController::class, 'index']);
    Route::post('students/import-excel', [Admin\StudentController::class, 'import_excel']);
    Route::get('students/{id}', [Admin\StudentController::class, 'read']);
    Route::post('students/{id}', [Admin\StudentController::class, 'update']);

    Route::get('school-time', [Admin\SchoolTimeController::class, 'index']);
    Route::post('school-time', [Admin\SchoolTimeController::class, 'store']);
    Route::post('school-time/{id}', [Admin\SchoolTimeController::class, 'update']);

    Route::get('profile', [Admin\ProfileController::class, 'index']);
    Route::put('profile/{id}', [Admin\ProfileController::class, 'update']);
    Route::get('profile/edit', [Admin\ProfileController::class, 'edit']);
    Route::get('profile/edit-password', [Admin\ProfileController::class, 'edit_password']);
    Route::put('profile/change-password/{id}', [Admin\ProfileController::class, 'change_password']);

    Route::get('presence', [Admin\PresenceController::class, 'index']);
    Route::patch('presence/update', [Admin\PresenceController::class, 'update']);
});

Route::prefix('parent')->middleware(['auth:parent'])->group(function () {
    Route::get('/', [UserParent\DashboardController::class,'index'])->name('parent.dashboard');
    Route::get('report', [UserParent\DashboardController::class,'index']);
    Route::get('permission', [UserParent\PermissionController::class, 'index']);
    Route::post('permission', [UserParent\PermissionController::class,'store']);

    // AJAX Route
    Route::get('ajax/school-time', [UserParent\DashboardController::class, 'schoolTime']);
    Route::get('ajax/recap-absent', [UserParent\DashboardController::class, 'getRecapAbsent']);
    Route::post('ajax/recap-absent', [UserParent\DashboardController::class, 'postRecapAbsent']);
    Route::get('ajax/daily-absent', [UserParent\DashboardController::class, 'dailyAbsent']);
});


// require __DIR__ . '/auth.php';
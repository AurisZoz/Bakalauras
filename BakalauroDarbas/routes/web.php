<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthManager;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CreateController;
use App\Http\Controllers\RehabilitationPlanController;
use App\Http\Controllers\Api\FileUploadController;
use App\Http\Controllers\Api\PictureController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\UserController;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\MessageController;

Auth::routes(['verify' => true]);

Route::get('/login', [AuthManager::class, 'login'])->name('login');
Route::post('/login', [AuthManager::class, 'loginPost'])->name('login.post');
Route::get('/registration', [AuthManager::class, 'registration'])->name('registration');
Route::post('/registration', [AuthManager::class, 'registrationPost'])->name('registration.post');
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ForgotPasswordController::class, 'reset'])->name('password.update');
Route::get('/logout', [AuthManager::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', function() {
        return view('main');
    })->name('main');

    Route::get('/system/profile', [ProfileController::class, 'showProfile'])->name('profile');
    Route::post('/system/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('/system/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/system/change-password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::get('/system/account-management', [ProfileController::class, 'showAccountManagement'])->name('account.management');
    Route::delete('/system/account-management/delete', [ProfileController::class, 'deleteAccount'])->name('account.delete');

    Route::get('/plan/plan-control', [RehabilitationPlanController::class, 'index'])->middleware(['auth', 'role:admin', 'role:doctor'])->name('plan.index');
    Route::get('/plan/plan/{id}', [RehabilitationPlanController::class, 'show'])->name('plan.show');
    Route::get('/plan/create', [CreateController::class, 'create'])->middleware(['auth', 'role:admin', 'role:doctor'])->name('plan.create');
    Route::post('/plan/entry/store', [CreateController::class, 'store'])->middleware(['auth', 'role:admin', 'role:doctor'])->name('plan.store');
    Route::get('/plan/plan/{id}/edit', [RehabilitationPlanController::class, 'edit'])->middleware(['auth', 'role:admin', 'role:doctor'])->name('plan.edit');
    Route::put('/plan/plan/{id}', [RehabilitationPlanController::class, 'update'])->middleware(['auth', 'role:admin', 'role:doctor'])->name('plan.update');
    Route::delete('/plan/plan/{id}', [RehabilitationPlanController::class, 'destroy'])->middleware(['auth', 'role:admin', 'role:doctor'])->name('plan.destroy');
    Route::get('/plan/all-plans', [RehabilitationPlanController::class, 'allPlans'])->middleware(['auth', 'role:admin', 'role:doctor'])->name('plan.all-plans');
    Route::get('/plan/saved-plans', [RehabilitationPlanController::class, 'savedPlans'])->middleware(['auth', 'role:admin', 'role:doctor'])->name('plan.saved-plans');
    Route::post('/plan/{id}/save', [RehabilitationPlanController::class, 'save'])->middleware(['auth', 'role:admin', 'role:doctor'])->name('plan.save');
    Route::delete('/plan/saved/{id}', [RehabilitationPlanController::class, 'unsave'])->middleware(['auth', 'role:admin', 'role:doctor'])->name('plan.unsave');

    Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto']);
    Route::delete('/profile/delete-photo', [ProfileController::class, 'deletePhoto']);
    Route::get('/files/{id}/download', [FileUploadController::class, 'download'])->name('file.download');

    Route::get('/admin/user-profiles', [AdminController::class, 'showUserProfiles'])->middleware(['auth', 'role:admin'])->name('admin.userProfiles');
    Route::get('/admin/usercontrol', [UserController::class, 'index'])->middleware(['auth', 'role:admin'])->name('usercontrol.index'); 
    Route::get('/api/users/search', [UserController::class, 'search'])->middleware(['auth', 'role:admin'])->name('usercontrol.search'); 
    Route::put('/admin/usercontrol/{id}', [UserController::class, 'update'])->middleware(['auth', 'role:admin'])->name('usercontrol.update');
    Route::delete('/admin/usercontrol/{id}', [UserController::class, 'destroy'])->middleware(['auth', 'role:admin'])->name('usercontrol.destroy');
    Route::get('/admin/usercontrol/{id}', [AdminController::class, 'showUser'])->middleware(['auth', 'role:admin'])->name('usercontrol.show');

    Route::get('/appointments/create', [AppointmentController::class, 'create'])->middleware(['auth', 'role:admin', 'role:doctor'])->name('appointment.create');
    Route::post('/appointments/store', [AppointmentController::class, 'store'])->middleware(['auth', 'role:admin', 'role:doctor'])->name('appointment.store');
    Route::get('/appointments/watch', [AppointmentController::class, 'watch'])->name('appointments.watch');
    Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->middleware(['auth', 'role:admin', 'role:doctor'])->name('appointment.edit');
    Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->middleware(['auth', 'role:admin', 'role:doctor'])->name('appointment.update');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->middleware(['auth', 'role:admin', 'role:doctor'])->name('appointment.destroy');
    Route::get('/user/view', [UserController::class, 'userView'])->name('user.view');

    Route::get('/user-contacts', [UserController::class, 'userContacts'])->name('user.contacts');

    Route::get('/messages', function () {
        return view('plan.messages'); 
    })->name('messages.index');
    
Route::get('/messages/{userId}', [MessageController::class, 'index']); 
Route::post('/messages', [MessageController::class, 'store']);

});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

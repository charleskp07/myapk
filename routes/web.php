<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AssignationController;
use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Admin\EvaluationController;
use App\Http\Controllers\Admin\NoteController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\Settings\AppreciationSettingController;
use App\Http\Controllers\Admin\Settings\BaremeSettingController;
use App\Http\Controllers\Admin\Settings\BreakdownSettingController;
use App\Http\Controllers\Admin\Settings\SettingViewsController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Auth\Processing\AuthController as ProcessingAuthController;
use App\Http\Controllers\Auth\Views\AuthController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, "home"])->name("home");

Route::get('/login', [AuthController::class, "login"])->name("login");
Route::post('/Login', [ProcessingAuthController::class, "login"])->name("auth.login");

Route::get('/two-factor', [AuthController::class, 'showTwoFactorForm'])->name('auth.two-factor');
Route::post('/two-factor/verify', [ProcessingAuthController::class, 'verifyTwoFactor'])->name('auth.two-factor.verify');
Route::post('/two-factor/resend', [ProcessingAuthController::class, 'resendTwoFactorCode'])->name('auth.two-factor.resend');


Route::get('/Mot de passe oublié', [AuthController::class, "forgottenPassword"])->name("auth.forgottenpassword");
Route::post('/Mot de passe oublié', [ProcessingAuthController::class, "forgottenPassword"])->name("auth.forgottenpassword");

Route::get('/otpcode', [AuthController::class, "otpCode"])->name("auth.otpcode");
Route::post('/otpcode', [ProcessingAuthController::class, "otpCode"])->name("auth.otpcode");

Route::get('/newPassword', [AuthController::class, "newPassword"])->name("auth.newpassword");
Route::post('/newPassword', [ProcessingAuthController::class, "newPassword"])->name("auth.newpassword");

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminController::class, "dashboard"])->name("dashboard");
    Route::get('/logout', [ProcessingAuthController::class, "logout"])->name('logout');
    
    Route::resource('/classrooms', ClassroomController::class);
    Route::resource('/teachers', TeacherController::class);
    Route::resource('/students', StudentController::class);
    Route::resource('/subjects', SubjectController::class);
    Route::resource('/assignations', AssignationController::class);
    Route::resource('/evaluations', EvaluationController::class);
    Route::get('/notes/create', [NoteController::class, 'create'])->name('notes.create');
    Route::get('/notes/{id}/edit', [NoteController::class, 'edit'])->name('notes.edit');
    Route::put('/notes/{id}/update', [NoteController::class, 'update'])->name('notes.update');
    Route::post('/notes/store', [NoteController::class, 'store'])->name('notes.store');
    Route::resource('/payments', PaymentController::class);
    
    
    //Routes des paramtrages
    Route::get('/settings', [SettingViewsController::class, "settingsIndex"])->name("admin.settings.index");
    Route::get('/settings/note-settings', [SettingViewsController::class, "noteSettingsIndex"])->name("admin.settings.notes.index");
    Route::resource('/settings/baremes', BaremeSettingController::class)->names('admin.baremes');
    Route::resource('/settings/note-appreciations', AppreciationSettingController::class)->names('admin.appreciations');
    Route::resource('/settings/breakdowns', BreakdownSettingController::class)->names('admin.breakdowns');


    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/photo', [ProfileController::class, 'deleteProfilePicture'])->name('profile.photo.delete');
});

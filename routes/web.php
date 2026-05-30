<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventManagementController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EvaluationController;

use App\Http\Controllers\MailerAccountController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Email-change verification (token-authenticated, no session auth required)
Route::get('/profile/verify-email/{token}', [\App\Http\Controllers\ProfileController::class, 'verifyEmailChange'])->name('profile.verify-email');

// Authenticated routes
Route::middleware('auth')->group(function () {

    // Documentation
    Route::get('/docs', fn() => view('docs.index'))->name('docs');

    // Dashboard / Kanban
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/switch/{event}', [DashboardController::class, 'switchEvent'])->name('dashboard.switch');

    // Events
    Route::resource('events', EventController::class);
    Route::get('/event-management/{event}', [EventManagementController::class, 'show'])->name('event-management.show');

    // Tasks (AJAX)
    Route::post('/tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');
    Route::resource('tasks', TaskController::class)->only(['store', 'update', 'destroy', 'show']);
    Route::post('/tasks/{task}/move', [TaskController::class, 'moveColumn'])->name('tasks.move');

    // Members
    Route::resource('members', MemberController::class)->parameters(['members' => 'member']);

    // Departments
    Route::resource('departments', DepartmentController::class)->only(['index', 'store', 'update', 'destroy']);

    // Evaluations — leadership management
    Route::middleware('role:president,vice_president')->group(function () {
        Route::get('/evaluations', [EvaluationController::class, 'index'])->name('evaluations.index');
        Route::get('/evaluations/create', [EvaluationController::class, 'create'])->name('evaluations.create');
        Route::post('/evaluations', [EvaluationController::class, 'store'])->name('evaluations.store');
        Route::get('/evaluations/{evaluation}', [EvaluationController::class, 'show'])->name('evaluations.show');
        Route::post('/evaluations/{evaluation}/open', [EvaluationController::class, 'openEvaluation'])->name('evaluations.open');
        Route::post('/evaluations/{evaluation}/close', [EvaluationController::class, 'closeEvaluation'])->name('evaluations.close');
    });

    // Evaluations — member fill form
    Route::get('/evaluations/{evaluation}/fill', [EvaluationController::class, 'form'])->name('evaluations.form');
    Route::post('/evaluations/{evaluation}/submit', [EvaluationController::class, 'submitForm'])->name('evaluations.submit');
    Route::get('/evaluations/{evaluation}/thankyou', [EvaluationController::class, 'thankyou'])->name('evaluations.thankyou');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar', [\App\Http\Controllers\ProfileController::class, 'updateAvatar'])->name('profile.avatar');

    // Update own status
    Route::patch('/profile/status', [\App\Http\Controllers\ProfileController::class, 'updateStatus'])->name('profile.status');
    Route::delete('/profile/cancel-email-change', [\App\Http\Controllers\ProfileController::class, 'cancelEmailChange'])->name('profile.cancel-email-change');

    // Notifications
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    // Email Accounts (president & vice president only)
    Route::middleware('role:president,vice_president')->group(function () {
        Route::get('/mailer-accounts', [MailerAccountController::class, 'index'])->name('mailer-accounts.index');
        Route::post('/mailer-accounts', [MailerAccountController::class, 'store'])->name('mailer-accounts.store');
        Route::put('/mailer-accounts/{mailerAccount}', [MailerAccountController::class, 'update'])->name('mailer-accounts.update');
        Route::delete('/mailer-accounts/{mailerAccount}', [MailerAccountController::class, 'destroy'])->name('mailer-accounts.destroy');
        Route::post('/mailer-accounts/{mailerAccount}/test', [MailerAccountController::class, 'test'])->name('mailer-accounts.test');
    });
});

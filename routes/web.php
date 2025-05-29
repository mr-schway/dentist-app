<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentActionController;
use App\Models\User;
use Illuminate\Http\Request;

// Public home
Route::get('/', function () {
    return view('welcome');
});

// Dashboard - only for authenticated, verified, and approved users
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'approved'])
    ->name('dashboard');

// Authenticated and approved users only
Route::middleware(['auth', 'approved'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Appointments
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');

    Route::resource('appointments', AppointmentController::class)->only(['edit', 'update', 'destroy']);

    // Appointment actions
    Route::patch('/appointments/{appointment}/approve', [AppointmentActionController::class, 'approve'])->name('appointments.approve');
    Route::patch('/appointments/{appointment}/cancel', [AppointmentActionController::class, 'cancel'])->name('appointments.cancel');
});

// Auth routes (login, register, etc.)
require __DIR__.'/auth.php';

// Admin panel (authenticated, verified, admin)
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');
    Route::resource('dentists', App\Http\Controllers\Admin\DentistController::class);
    Route::resource('appointments', App\Http\Controllers\Admin\AdminAppointmentController::class);
});

// Admin user approval/rejection (authenticated, verified, admin)
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::post('/admin/users/{user}/approve', function (User $user) {
        $user->is_approved = true;
        $user->save();
        return redirect()->back()->with('success', 'User approved!');
    })->name('admin.users.approve');

    Route::post('/admin/users/{user}/reject', function (User $user) {
        $user->delete();
        return redirect()->back()->with('success', 'User rejected!');
    })->name('admin.users.reject');
});



Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');
        
        Route::resource('dentists', App\Http\Controllers\Admin\DentistController::class);
        Route::resource('appointments', App\Http\Controllers\Admin\AdminAppointmentController::class);

        // Users resource route (excluding create and store)
        Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['create', 'store']);
    });

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\SchoolRegisterController;
use App\Http\Controllers\Auth\RegisterSelectionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SchoolController as AdminSchoolController;
use App\Http\Controllers\Admin\PlayerController as AdminPlayerController;
use App\Http\Controllers\School\DashboardController as SchoolDashboardController;
use App\Http\Controllers\School\ProfileController as SchoolProfileController;
use App\Http\Controllers\School\PlayerController as SchoolPlayerController;
use App\Http\Controllers\Player\DashboardController as PlayerDashboardController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\AboutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| All routes for the Find11 application
| Routes are organized by role: Admin, School, Player
|--------------------------------------------------------------------------
*/

// Public Website Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Registration Routes
Route::get('/register', [RegisterSelectionController::class, 'index'])
    ->middleware('guest')
    ->name('register');

Route::get('/register/school', [SchoolRegisterController::class, 'create'])
    ->middleware('guest')
    ->name('school.register');
Route::post('/register/school', [SchoolRegisterController::class, 'store'])
    ->middleware('guest');

// Default dashboard redirect (redirects based on role)
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    return match($user->role) {
        'ADMIN' => redirect()->route('admin.dashboard'),
        'SCHOOL' => redirect()->route('school.dashboard'),
        'PLAYER' => redirect()->route('player.dashboard'),
        default => view('dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes (Breeze default)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Protected by 'auth' and 'role:ADMIN' middleware
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', 'role:ADMIN'])
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // School Management
        Route::get('/schools', [AdminSchoolController::class, 'index'])->name('schools.index');
        Route::get('/schools/pending', [AdminSchoolController::class, 'pending'])->name('schools.pending');
        Route::get('/schools/{school}', [AdminSchoolController::class, 'show'])->name('schools.show');
        Route::post('/schools/{school}/approve', [AdminSchoolController::class, 'approve'])->name('schools.approve');
        Route::post('/schools/{school}/reject', [AdminSchoolController::class, 'reject'])->name('schools.reject');

        // Player Management (Read-only)
        Route::get('/players', [AdminPlayerController::class, 'index'])->name('players.index');
        Route::get('/players/{player}', [AdminPlayerController::class, 'show'])->name('players.show');
    });

/*
|--------------------------------------------------------------------------
| School Routes
|--------------------------------------------------------------------------
| Protected by 'auth', 'role:SCHOOL', and 'approved.school' middleware
|--------------------------------------------------------------------------
*/
Route::prefix('school')
    ->middleware(['auth', 'role:SCHOOL', 'approved.school'])
    ->name('school.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [SchoolDashboardController::class, 'index'])->name('dashboard');

        // Profile Management
        Route::get('/profile', [SchoolProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/edit', [SchoolProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [SchoolProfileController::class, 'update'])->name('profile.update');

        // Player Management (CRUD)
        Route::resource('players', SchoolPlayerController::class);
    });

/*
|--------------------------------------------------------------------------
| Player Routes
|--------------------------------------------------------------------------
| Protected by 'auth' and 'role:PLAYER' middleware
| Note: Players are created by schools, not self-registered
|--------------------------------------------------------------------------
*/
Route::prefix('player')
    ->middleware(['auth', 'role:PLAYER'])
    ->name('player.')
    ->group(function () {
        // Dashboard (View-only profile)
        Route::get('/dashboard', [PlayerDashboardController::class, 'index'])->name('dashboard');
    });

// Include Breeze auth routes
require __DIR__.'/auth.php';

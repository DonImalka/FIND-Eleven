<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\SchoolRegisterController;
use App\Http\Controllers\Auth\RegisterSelectionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SchoolController as AdminSchoolController;
use App\Http\Controllers\Admin\PlayerController as AdminPlayerController;
use App\Http\Controllers\Admin\PlayerCategoryController as AdminPlayerCategoryController;
use App\Http\Controllers\Admin\TournamentController as AdminTournamentController;
use App\Http\Controllers\Admin\MatchController as AdminMatchController;
use App\Http\Controllers\School\DashboardController as SchoolDashboardController;
use App\Http\Controllers\School\ProfileController as SchoolProfileController;
use App\Http\Controllers\School\PlayerController as SchoolPlayerController;
use App\Http\Controllers\School\MatchController as SchoolMatchController;
use App\Http\Controllers\School\LiveScoringController;
use App\Http\Controllers\School\PlayerStatController;
use App\Http\Controllers\Player\DashboardController as PlayerDashboardController;
use App\Http\Controllers\Player\HelpPostController as PlayerHelpPostController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\AboutController;
use App\Http\Controllers\Website\LiveScoreController;
use App\Http\Controllers\Website\RankingController;
use App\Http\Controllers\Website\HelpPostController as WebsiteHelpPostController;
use App\Http\Controllers\School\HelpPostController as SchoolHelpPostController;
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

// Live Scores (public)
Route::get('/live-scores', [LiveScoreController::class, 'index'])->name('live-scores.index');
Route::get('/live-scores/{cricketMatch}', [LiveScoreController::class, 'show'])->name('live-scores.show');
Route::get('/live-scores/{cricketMatch}/data', [LiveScoreController::class, 'data'])->name('live-scores.data');

// Rankings (public)
Route::get('/rankings', [RankingController::class, 'index'])->name('rankings.index');

// Help Posts (public)
Route::get('/help-posts', [WebsiteHelpPostController::class, 'index'])->name('help-posts.index');
Route::get('/help-posts/{helpPost}', [WebsiteHelpPostController::class, 'show'])->name('help-posts.show');

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

        // Player Category Management
        Route::resource('player-categories', AdminPlayerCategoryController::class)->except(['show']);

        // Tournament Management
        Route::resource('tournaments', AdminTournamentController::class);

        // Match Management
        Route::get('/matches/create', [AdminMatchController::class, 'create'])->name('matches.create');
        Route::post('/matches', [AdminMatchController::class, 'store'])->name('matches.store');
        Route::get('/matches/{cricketMatch}', [AdminMatchController::class, 'show'])->name('matches.show');
        Route::get('/matches/{cricketMatch}/start', [AdminMatchController::class, 'startForm'])->name('matches.start-form');
        Route::post('/matches/{cricketMatch}/start', [AdminMatchController::class, 'start'])->name('matches.start');
        Route::post('/matches/{cricketMatch}/complete', [AdminMatchController::class, 'complete'])->name('matches.complete');
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

        // Player Stats — Initial / Existing Stats
        Route::get('/players/{player}/stats/initial', [PlayerStatController::class, 'editInitial'])->name('players.stats.initial');
        Route::put('/players/{player}/stats/initial', [PlayerStatController::class, 'updateInitial'])->name('players.stats.initial.update');

        // Player Stats — Match Performances
        Route::get('/players/{player}/stats/add', [PlayerStatController::class, 'create'])->name('players.stats.create');
        Route::post('/players/{player}/stats', [PlayerStatController::class, 'store'])->name('players.stats.store');
        Route::get('/players/{player}/stats/history', [PlayerStatController::class, 'history'])->name('players.stats.history');
        Route::delete('/players/{player}/stats/{performance}', [PlayerStatController::class, 'destroyPerformance'])->name('players.stats.destroy');

        // Match Management
        Route::get('/matches', [SchoolMatchController::class, 'index'])->name('matches.index');
        Route::get('/matches/{cricketMatch}', [SchoolMatchController::class, 'show'])->name('matches.show');
        Route::get('/matches/{cricketMatch}/squad', [SchoolMatchController::class, 'editSquad'])->name('matches.squad');
        Route::put('/matches/{cricketMatch}/squad', [SchoolMatchController::class, 'updateSquad'])->name('matches.squad.update');

        // Live Scoring
        Route::get('/matches/{cricketMatch}/score', [LiveScoringController::class, 'show'])->name('matches.score');
        Route::post('/matches/{cricketMatch}/score', [LiveScoringController::class, 'update'])->name('matches.score.update');
        Route::post('/matches/{cricketMatch}/switch-innings', [LiveScoringController::class, 'switchInnings'])->name('matches.switch-innings');

        // Help Posts Management
        Route::get('/help-posts', [SchoolHelpPostController::class, 'index'])->name('help-posts.index');
        Route::get('/help-posts/{helpPost}', [SchoolHelpPostController::class, 'show'])->name('help-posts.show');
        Route::post('/help-posts/{helpPost}/approve', [SchoolHelpPostController::class, 'approve'])->name('help-posts.approve');
        Route::post('/help-posts/{helpPost}/reject', [SchoolHelpPostController::class, 'reject'])->name('help-posts.reject');
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

        // Help Posts
        Route::get('/help-posts', [PlayerHelpPostController::class, 'index'])->name('help-posts.index');
        Route::get('/help-posts/create', [PlayerHelpPostController::class, 'create'])->name('help-posts.create');
        Route::post('/help-posts', [PlayerHelpPostController::class, 'store'])->name('help-posts.store');
        Route::get('/help-posts/{helpPost}', [PlayerHelpPostController::class, 'show'])->name('help-posts.show');
        Route::delete('/help-posts/{helpPost}', [PlayerHelpPostController::class, 'destroy'])->name('help-posts.destroy');
    });

// Include Breeze auth routes
require __DIR__.'/auth.php';

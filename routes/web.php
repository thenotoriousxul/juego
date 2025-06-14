<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\MoveController;
use App\Http\Controllers\GameHistoryController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'auth' => [
            'user' => auth()->user()
        ]
    ]);
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/games', [GameController::class, 'index'])->name('games.index');
    Route::post('/games', [GameController::class, 'store'])->name('games.store');
    Route::get('/games/{game}', [GameController::class, 'show'])->name('games.show');
    Route::post('/games/{game}/join', [GameController::class, 'join'])->name('games.join');
    Route::post('/games/{game}/move', [MoveController::class, 'store'])->name('games.move');
    Route::post('/games/{game}/surrender', [GameController::class, 'surrender'])->name('games.surrender');
    Route::delete('/games/{game}', [GameController::class, 'destroy'])->name('games.destroy');

    Route::get('/games/{game}/board/{user}', [\App\Http\Controllers\BoardController::class, 'show'])->name('boards.show');

    Route::get('/stats', [ProfileController::class, 'stats'])->name('user.stats');
    Route::get('/history/{game}', [GameHistoryController::class, 'show'])->name('user.history');
});


Route::get('/countries', [LocationController::class, 'countries']);

Route::get('/location-select', [LocationController::class, 'showForm'])->name('location.select');

Route::get('/cities/{countryId}', [LocationController::class, 'citiesByCountry']);

Route::post('/cities', [LocationController::class, 'storeCity']);

require __DIR__.'/auth.php';

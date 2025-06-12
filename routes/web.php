<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\LocationController;


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
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para el juego naval
    Route::get('/games', [\App\Http\Controllers\GameController::class, 'index'])->name('games.index');
    Route::post('/games', [\App\Http\Controllers\GameController::class, 'store'])->name('games.store');
    Route::post('/games/{game}/join', [\App\Http\Controllers\GameController::class, 'join'])->name('games.join');
    Route::get('/games/{game}', [\App\Http\Controllers\GameController::class, 'show'])->name('games.show');

    Route::get('/games/{game}/board/{user}', [\App\Http\Controllers\BoardController::class, 'show'])->name('boards.show');

    Route::post('/games/{game}/move', [\App\Http\Controllers\MoveController::class, 'store'])->name('moves.store');
    Route::get('/games/{game}/history', [\App\Http\Controllers\MoveController::class, 'history'])->name('moves.history');

    Route::get('/stats', [\App\Http\Controllers\ProfileController::class, 'stats'])->name('user.stats');
    Route::get('/history/{game}', [\App\Http\Controllers\ProfileController::class, 'gameHistory'])->name('user.gameHistory');
});


Route::get('/countries', [LocationController::class, 'countries']);

Route::get('/location-select', [LocationController::class, 'showForm'])->name('location.select');

Route::get('/cities/{countryId}', [LocationController::class, 'citiesByCountry']);

Route::post('/cities', [LocationController::class, 'storeCity']);

require __DIR__.'/auth.php';

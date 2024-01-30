<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameScoreController;
use App\Http\Controllers\PlayerController;
use Illuminate\Support\Facades\Route;

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
Route::get('/dashboard', function () {
    return redirect('/');
});

Route::group(['middleware' => ['auth', 'verified']], function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/game/new', [GameController::class, 'new'])->name('game.new');
    Route::get('/game/{id}/edit', [GameController::class, 'edit'])->name('game.edit');
    Route::get('/game/{id}/show', [GameController::class, 'show'])->name('game.show');
    Route::post('/game', [GameController::class, 'store'])->name('game.store');
    Route::patch('/game', [GameController::class, 'update'])->name('game.update');
    Route::delete('/game', [GameController::class, 'delete'])->name('game.delete');
    Route::post('/game/score', [GameScoreController::class, 'store'])->name('game.score.store');
    Route::delete('/game/score', [GameScoreController::class, 'delete'])->name('game.score.delete');
    Route::get('/games', [GameController::class, 'overview'])->name('games');

    Route::get('/players', [PlayerController::class, 'overview'])->name('players');
    Route::get('/player/{id}', [PlayerController::class, 'show'])->name('player.show');
    Route::post('/player', [PlayerController::class, 'store'])->name('player.store');
    Route::patch('/player', [PlayerController::class, 'update'])->name('player.update');
});

require __DIR__.'/auth.php';

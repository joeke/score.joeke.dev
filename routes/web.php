<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameScoreController;
use App\Http\Controllers\PlayerController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::group(['middleware' => ['auth', 'verified']], function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/game/new', [GameController::class, 'new'])->name('game.new');
    Route::get('/game/{id}', [GameController::class, 'show'])->name('game.show');
    Route::post('/game', [GameController::class, 'store'])->name('game.store');
    Route::post('/game/score', [GameScoreController::class, 'store'])->name('game.score.store');
    Route::delete('/game/score', [GameScoreController::class, 'delete'])->name('game.score.delete');

    Route::get('/players', [PlayerController::class, 'index'])->name('players');
    Route::get('/player/new', [PlayerController::class, 'new'])->name('player.new');
});

require __DIR__.'/auth.php';

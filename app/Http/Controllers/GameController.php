<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use App\Models\Game;

class GameController extends Controller
{
    /**
     * Display the new game form.
     */
    public function new(): Response
    {
        return Inertia::render('Game/New');
    }

    /**
     * Handle creating a new game.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => 'required',
            'player_1' => 'required'
        ]);

        $game = Game::create([
            // TODO
        ]);

        return redirect()->route('game.show', ['id' => 1]);
    }

    /**
     * Show the game form.
     */
    public function show(Game $game): Response
    {
        return Inertia::render('Game/Show', [
          'game' => $game
        ]);
    }
}

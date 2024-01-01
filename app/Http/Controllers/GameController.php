<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use App\Models\Game;
use App\Models\GameType;
use App\Models\User;

class GameController extends Controller
{
    /**
     * Display the new game form.
     */
    public function new(): Response
    {
        $players = User::all();
        $types = GameType::all();

        return Inertia::render('Game/New', [
            'players' => $players,
            'gameTypes' => $types
        ]);
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
            'player_id' => 'required',
            'score_goal' => 'required',
        ]);

        $game = Game::create([
            'type' => $request->type,
            'player_id' => $request->player_id ?? $request->user()->id,
            'opponent_id' => $request->opponent_id,
            'score_goal' => $request->score_goal,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => $request->user()->id
        ]);

        return redirect()->route('game.show', ['id' => $game->id]);
    }

    /**
     * Show the game form.
     */
    public function show(int $id): Response
    {
        $game = Game::where('id', $id)->with('scores')->first();

        return Inertia::render('Game/Show', [
            'game' => $game,
            'scores' => $this->calculateScores($game)
        ]);
    }

    private function calculateScores($game)
    {
        $scores = $game->scores;

        $playerTotal = 0;
        $opponentTotal = 0;

        foreach ($scores as $score) {
            if ($score->player_id == $game->player_id) {
                $playerTotal += $score->points;
            } else {
                $opponentTotal += $score->points;
            }
        }

        return [
            'player' => [
                'total' => $playerTotal,
            ],
            'opponent' => [
                'total' => $opponentTotal
            ]
        ];
    }
}

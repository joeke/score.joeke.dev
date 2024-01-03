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

        $scores = $this->calculateScores($game);

        return Inertia::render('Game/Show', [
            'game' => $game,
            'players' => [
                $game->player_id => [
                    'id' => $game->player_id,
                    'name' => $this->getPlayerName($game->player_id),
                    'total_points' => $scores && $scores[$game->player_id] ? $scores[$game->player_id]['total_points'] : 0,
                ],
                $game->opponent_id => [
                    'id' => $game->opponent_id,
                    'name' => $this->getPlayerName($game->opponent_id),
                    'total_points' => $scores && $scores[$game->opponent_id] ? $scores[$game->opponent_id]['total_points'] : 0,
                ]
            ],
            'scores' => $this->calculateScores($game)
        ]);
    }

    private function calculateScores($game)
    {
        $scores = $game->scores->groupBy('player_id');

        $output = [];

        foreach ($scores as $playerId => $innings) {
            $totalPoints = 0;
            $foulPoints = 0;

            foreach ($innings as $score) {
                $totalPoints += ($score->points - $score->foul_points);
                $foulPoints += $score->foul_points;
            }

            $output[$playerId] = [
                'id' => $playerId,
                'name' => $this->getPlayerName($playerId),
                'total_points' => $totalPoints,
                'foul_points' => $foulPoints,
                'high_run' => $innings->max('points'),
                'ppi' => round($totalPoints / count($innings), 2),
                'innings_count' => count($innings),
                'innings' => $innings,
            ];
        }

        return $output;
    }

    private function getPlayerName($id)
    {
        $player = User::where('id', $id)->first();

        return $player->name;
    }
}

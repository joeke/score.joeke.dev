<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Game;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $games = Game::where('created_by', auth()->user()->id)
            ->with(['player', 'opponent', 'type'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return Inertia::render('Dashboard', [
            'games' => $games,
            'statistics' => $this->statistics()
        ]);
    }

    private function statistics()
    {
        $games = Game::where('player_id', auth()->user()->id)
            ->orWhere('opponent_id', auth()->user()->id)
            ->with(['scores' => function ($query) {
                $query->where('player_id', auth()->user()->id);
            }])
            ->get();

        $scores = [];
        $allScores = [];
        $highRuns = [];

        foreach ($games as $game) {
            $scores[] = collect($game->scores)->toArray();
        }

        if (count($scores)) {
            $allScores = collect($scores)->collapse()->sortBy('points', SORT_NUMERIC, true)->toArray();

            $highRuns = collect($allScores)->map(function ($score) {
                return $score['points'];
            })->flatten()->slice(0, 3)->toArray();
        }

        // Find all games versus an opponent
        $versusGames = $games->filter(function ($game) {
            return ($game->player_id === auth()->user()->id && $game->opponent_id > 0) || $game->opponent_id === auth()->user()->id;
        });

        return [
            'games' => [
                'total' => $games->count(),
                'versus' => $versusGames->count(),
                'solo' => $games->count() - $versusGames->count()
            ],
            'wins' => 0, // TODO
            'losses' => 0, // TODO
            'highRuns' => $highRuns,
            'averageRun' => $allScores ? round(collect($allScores)->avg('points'), 2) : 0,
            'totalPoints' => $allScores ? collect($allScores)->sum('points') : 0,
            'totalFoulPoints' => $allScores ? collect($allScores)->sum('foul_points') : 0,
            'totalInnings' => $allScores ? collect($allScores)->count() : 0
        ];
    }
}

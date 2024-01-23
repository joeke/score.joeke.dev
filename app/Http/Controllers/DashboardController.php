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
        foreach ($games as $game) {
            $scores[] = collect($game->scores)->toArray();
        }

        $allScores = collect($scores)->collapse()->sortBy('points', SORT_NUMERIC, true)->toArray();

        $highRuns = collect($allScores)->map(function ($score) {
            return $score['points'];
        })->flatten()->slice(0, 3)->toArray();

        return [
            'gamesCount' => $games->count(),
            'wins' => 0, // TODO
            'losses' => 0, // TODO
            'highRuns' => $highRuns,
            'averageRun' => round(collect($allScores)->avg('points'), 2),
            'totalPoints' => collect($allScores)->sum('points'),
            'totalFoulPoints' => collect($allScores)->sum('foul_points'),
            'totalInnings' => collect($allScores)->count()
        ];
    }
}

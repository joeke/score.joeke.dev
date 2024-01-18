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
            'games' => $games
        ]);
    }
}

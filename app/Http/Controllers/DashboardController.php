<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Game;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $games = Game::where('created_by', auth()->user()->id)->orderBy('created_at', 'desc')->get();

        return Inertia::render('Dashboard', [
            'games' => $games
        ]);
    }
}

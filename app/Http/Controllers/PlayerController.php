<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;

class PlayerController extends Controller
{
    public function overview(): Response
    {
        $players = User::all();

        return Inertia::render('Players', [
            'players' => $players
        ]);
    }
}
